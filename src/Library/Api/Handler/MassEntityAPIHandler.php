<?php

namespace Zoho\CRM\Library\Api\Handler;

class MassEntityAPIHandler extends APIHandler
{
    private $module = null;

    public function __construct($moduleInstance)
    {
        $this->module = $moduleInstance;
    }

    public static function getInstance($moduleInstance)
    {
        return new self($moduleInstance);
    }

    public function createRecords($records, $trigger)
    {
        if (count($records) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = $this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            $this->addHeader('Content-Type', 'application/json');
            $requestBodyObj = [];
            $dataArray = [];
            foreach ($records as $record) {
                if ($record->getEntityId() == null) {
                    array_push($dataArray, EntityAPIHandler::getInstance($record)->getZCRMRecordAsJSON());
                } else {
                    throw new ZCRMException('Entity ID MUST be null for create operation.', APIConstants::RESPONSECODE_BAD_REQUEST);
                }
            }
            $requestBodyObj['data'] = $dataArray;
            if ($trigger !== null && is_array($trigger)) {
                $requestBodyObj['trigger'] = $trigger;
            }
            $this->requestBody = $requestBodyObj;

            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $createdRecords = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $recordDetails = $responseData['details'];
                    $newRecord = $records[$i];
                    EntityAPIHandler::getInstance($newRecord)->setRecordProperties($recordDetails);
                    array_push($createdRecords, $newRecord);
                    $entityResIns->setData($newRecord);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($createdRecords);

            return $bulkAPIResponse;
    
    }

    public function upsertRecords($records)
    {
        if (count($records) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = $this->module->getAPIName().'/upsert';
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            $this->addHeader('Content-Type', 'application/json');
            $requestBodyObj = [];
            $dataArray = [];
            foreach ($records as $record) {
                $recordJSON = EntityAPIHandler::getInstance($record)->getZCRMRecordAsJSON();
                if ($record->getEntityId() != null) {
                    $recordJSON['id'] = $record->getEntityId();
                }
                array_push($dataArray, $recordJSON);
            }
            $requestBodyObj['data'] = $dataArray;
            $this->requestBody = $requestBodyObj;

            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $upsertRecords = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $recordDetails = $responseData['details'];
                    $newRecord = $records[$i];
                    EntityAPIHandler::getInstance($newRecord)->setRecordProperties($recordDetails);
                    array_push($upsertRecords, $newRecord);
                    $entityResIns->setData($newRecord);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($upsertRecords);

            return $bulkAPIResponse;
    }

    public function updateRecords($records, $trigger)
    {
        if (count($records) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = $this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_PUT;
            $this->addHeader('Content-Type', 'application/json');
            $requestBodyObj = [];
            $dataArray = [];
            foreach ($records as $record) {
                $recordJSON = EntityAPIHandler::getInstance($record)->getZCRMRecordAsJSON();
                if ($record->getEntityId() != null) {
                    $recordJSON['id'] = $record->getEntityId();
                }
                array_push($dataArray, $recordJSON);
            }
            $requestBodyObj['data'] = $dataArray;
            if ($trigger !== null && is_array($trigger)) {
                $requestBodyObj['trigger'] = $trigger;
            }
            $this->requestBody = $requestBodyObj;

            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $upsertRecords = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $recordDetails = $responseData['details'];
                    $newRecord = $records[$i];
                    EntityAPIHandler::getInstance($newRecord)->setRecordProperties($recordDetails);
                    array_push($upsertRecords, $newRecord);
                    $entityResIns->setData($newRecord);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($upsertRecords);

            return $bulkAPIResponse;
    }

    public function deleteRecords($entityIds)
    {
        if (count($entityIds) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = $this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_DELETE;
            $this->addHeader('Content-Type', 'application/json');
            $this->addParam('ids', implode(',', $entityIds)); //converts array to string with specified seperator

            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $responses = $bulkAPIResponse->getEntityResponses();

            foreach ($responses as $entityResIns) {
                $responseData = $entityResIns->getResponseJSON();
                $responseJSON = $responseData['details'];
                $record = ZCRMRecord::getInstance($this->module->getAPIName(), $responseJSON['id']);
                $entityResIns->setData($record);
            }

            return $bulkAPIResponse;
    }

    public function getAllDeletedRecords()
    {
        return self::getDeletedRecords('all');
    }

    public function getRecycleBinRecords()
    {
        return self::getDeletedRecords('recycle');
    }

    public function getPermanentlyDeletedRecords()
    {
        return self::getDeletedRecords('permanent');
    }

    private function getDeletedRecords($type)
    {
        
            $this->urlPath = $this->module->getAPIName().'/deleted';
            $this->requestMethod = APIConstants::REQUEST_METHOD_GET;
            $this->addHeader('Content-Type', 'application/json');
            $this->addParam('type', $type);

            $responseInstance = APIRequest::getInstance($this)->getBulkAPIResponse();
            $responseJSON = $responseInstance->getResponseJSON();
            $trashRecords = $responseJSON['data'];
            $trashRecordList = [];
            foreach ($trashRecords as $trashRecord) {
                $trashRecordInstance = ZCRMTrashRecord::getInstance($trashRecord['type'], $trashRecord['id']);
                self::setTrashRecordProperties($trashRecordInstance, $trashRecord);
                array_push($trashRecordList, $trashRecordInstance);
            }

            $responseInstance->setData($trashRecordList);

            return $responseInstance;
    }

    public function setTrashRecordProperties($trashRecordInstance, $recordProperties)
    {
        if ($recordProperties['display_name'] != null) {
            $trashRecordInstance->setDisplayName($recordProperties['display_name']);
        }
        if ($recordProperties['created_by'] != null) {
            $createdBy = $recordProperties['created_by'];
            $createdBy_User = ZCRMUser::getInstance($createdBy['id'], $createdBy['name']);
            $trashRecordInstance->setCreatedBy($createdBy_User);
        }
        if ($recordProperties['deleted_by'] != null) {
            $deletedBy = $recordProperties['deleted_by'];
            $deletedBy_User = ZCRMUser::getInstance($deletedBy['id'], $deletedBy['name']);
            $trashRecordInstance->setDeletedBy($deletedBy_User);
        }
        $trashRecordInstance->setDeletedTime($recordProperties['deleted_time']);
    }

    public function getRecords($cvId, $sortByField, $sortOrder, $page, $perPage, $customHeaders)
    {
            $this->urlPath = $this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_GET;
            $this->addHeader('Content-Type', 'application/json');
            if ($customHeaders != null) {
                foreach ($customHeaders as $key=>$value) {
                    $this->addHeader($key, $value);
                }
            }
            if ($cvId != null) {
                $this->addParam('cvid', $cvId + 0);
            }
            if ($sortByField != null) {
                $this->addParam('sort_by', $sortByField);
            }
            if ($sortOrder != null) {
                $this->addParam('sort_order', $sortOrder);
            }
            $this->addParam('page', $page + 0);
            $this->addParam('per_page', $perPage + 0);

            $responseInstance = APIRequest::getInstance($this)->getBulkAPIResponse();
            $responseJSON = $responseInstance->getResponseJSON();
            $records = $responseJSON['data'];
            $recordsList = [];
            foreach ($records as $record) {
                $recordInstance = ZCRMRecord::getInstance($this->module->getAPIName(), $record['id']);
                EntityAPIHandler::getInstance($recordInstance)->setRecordProperties($record);
                array_push($recordsList, $recordInstance);
            }

            $responseInstance->setData($recordsList);

            return $responseInstance;
    }

    public function searchRecords($searchWord, $page, $perPage, $type)
    {
            $this->urlPath = $this->module->getAPIName().'/search';
            $this->requestMethod = APIConstants::REQUEST_METHOD_GET;
            $this->addHeader('Content-Type', 'application/json');
            switch ($type) {
                case 'word':
                    $this->addParam('word', $searchWord);
                    break;
                case 'phone':
                    $this->addParam('phone', $searchWord);
                    break;
                case 'email':
                    $this->addParam('email', $searchWord);
                    break;
                case 'criteria':
                    $this->addParam('criteria', $searchWord);
                    break;
            }
            $this->addParam('page', $page + 0);
            $this->addParam('per_page', $perPage + 0);
            $responseInstance = APIRequest::getInstance($this)->getBulkAPIResponse();
            $responseJSON = $responseInstance->getResponseJSON();
            $records = $responseJSON['data'];
            $recordsList = [];
            foreach ($records as $record) {
                $recordInstance = ZCRMRecord::getInstance($this->module->getAPIName(), $record['id']);
                EntityAPIHandler::getInstance($recordInstance)->setRecordProperties($record);
                array_push($recordsList, $recordInstance);
            }

            $responseInstance->setData($recordsList);

            return $responseInstance;
    }

    public function massUpdateRecords($idList, $apiName, $value)
    {
        if (count($idList) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $inputJSON = self::constructJSONForMassUpdate($idList, $apiName, $value);
            $this->urlPath = $this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_PUT;
            $this->addHeader('Content-Type', 'application/json');
            $this->requestBody = $inputJSON;
            $this->apiKey = 'data';
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();

            $updatedRecords = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $recordJSON = $responseData['details'];

                    $updatedRecord = ZCRMRecord::getInstance($this->module->getAPIName(), $recordJSON['id']);
                    EntityAPIHandler::getInstance($updatedRecord)->setRecordProperties($recordJSON);
                    array_push($updatedRecords, $updatedRecord);
                    $entityResIns->setData($updatedRecord);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($updatedRecords);

            return $bulkAPIResponse;
        
    }

    public function constructJSONForMassUpdate($idList, $apiName, $value)
    {
        $massUpdateArray = [];
        foreach ($idList as $id) {
            $updateJson = [];
            $updateJson['id'] = ''.$id;
            $updateJson[$apiName] = $value;
            array_push($massUpdateArray, $updateJson);
        }

        return ['data'=>$massUpdateArray];
    }
}
