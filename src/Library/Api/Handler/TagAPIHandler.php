<?php

namespace Zoho\CRM\Library\Api\Handler;

class TagAPIHandler extends APIHandler
{
    protected $module = null;

    private function __construct($zcrmmodule)
    {
        $this->module = $zcrmmodule;
    }

    public static function getInstance($zcrmmodule = null)
    {
        return new self($zcrmmodule);
    }

    public function getTags()
    {
        
            $this->urlPath = 'settings/tags?module='.$this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_GET;
            $this->addHeader('Content-Type', 'application/json');
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getBulkAPIResponse();
            $responseJSON = $responseInstance->getResponseJSON();
            $tags = $responseJSON['tags'];
            $tagsList = [];
            foreach ($tags as $tag) {
                $tagInstance = ZCRMTag::getInstance($tag['id']);
                self::setTagProperties($tagInstance, $tag);
                array_push($tagsList, $tagInstance);
            }
            $responseInstance->setData($tagsList);

            return $responseInstance;
       
    }

    public function getTagCount($tagId)
    {
        
            $this->requestMethod = APIConstants::REQUEST_METHOD_GET;
            $this->urlPath = 'settings/tags/'.$tagId.'/actions/records_count?module='.$this->module->getAPIName();
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();
            $tagDetails = $responseInstance->getResponseJSON();
            $tagInstance = ZCRMTag::getInstance($tagId);
            self::setTagProperties($tagInstance, $tagDetails);
            $responseInstance->setData($tagInstance);

            return $responseInstance;
   
    }

    public function createTags($tags)
    {
        if (count($tags) > 50) {
            throw new ZCRMException(APIConstants::API_MAX_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = 'settings/tags?module='.$this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            $this->addHeader('Content-Type', 'application/json');
            $requestBodyObj = [];
            $dataArray = [];
            foreach ($tags as $tag) {
                if ($tag->getId() == null) {
                    array_push($dataArray, self::getZCRMTagAsJSON($tag));
                } else {
                    throw new ZCRMException('Tag ID MUST be null for create operation.', APIConstants::RESPONSECODE_BAD_REQUEST);
                }
            }
            $requestBodyObj['tags'] = $dataArray;
            $this->requestBody = $requestBodyObj;
            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $createdTags = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $tagDetails = $responseData['details'];
                    $newTag = $tags[$i];
                    self::setTagProperties($newTag, $tagDetails);
                    array_push($createdTags, $newTag);
                    $entityResIns->setData($newTag);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($createdTags);

            return $bulkAPIResponse;
    
    }

    public function updateTags($tags)
    {
        if (count($tags) > 50) {
            throw new ZCRMException(APIConstants::API_MAX_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $this->urlPath = 'settings/tags?module='.$this->module->getAPIName();
            $this->requestMethod = APIConstants::REQUEST_METHOD_PUT;
            $this->addHeader('Content-Type', 'application/json');
            $requestBodyObj = [];
            $dataArray = [];
            foreach ($tags as $tag) {
                array_push($dataArray, self::getZCRMTagAsJSON($tag));
            }
            $requestBodyObj['tags'] = $dataArray;
            $this->requestBody = $requestBodyObj;
            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $updatedTags = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            $size = count($responses);
            for ($i = 0; $i < $size; $i++) {
                $entityResIns = $responses[$i];
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $tagDetails = $responseData['details'];
                    $updateTag = $tags[$i];
                    self::setTagProperties($updateTag, $tagDetails);
                    array_push($updatedTags, $updateTag);
                    $entityResIns->setData($updateTag);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($updatedTags);

            return $bulkAPIResponse;

    }

    public function delete($tagId)
    {
        
            $this->requestMethod = APIConstants::REQUEST_METHOD_DELETE;
            $this->urlPath = 'settings/tags/'.$tagId;
            $this->addHeader('Content-Type', 'application/json');
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();

            return $responseInstance;
       
    }

    public function merge($tagId, $mergeId)
    {
        
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            $this->urlPath = 'settings/tags/'.$tagId.'/actions/merge';
            $this->addHeader('Content-Type', 'application/json');
            $tagJSON = [];
            $tagJSON['conflict_id'] = ''.$mergeId;
            array_filter($tagJSON);
            $this->requestBody = json_encode(array_filter(['tags'=>[$tagJSON]]));
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();
            $responseDataArray = $responseInstance->getResponseJSON()['tags'];
            $responseData = $responseDataArray[0];
            $reponseDetails = $responseData['details'];
            $tag = ZCRMTag::getInstance($reponseDetails['id']);
            self::setTagProperties($tag, $reponseDetails);
            $responseInstance->setData($tag);

            return $responseInstance;
     
    }

    public function update($tag)
    {
        
            $this->requestMethod = APIConstants::REQUEST_METHOD_PUT;
            $this->urlPath = 'settings/tags/'.$tag->getId().'?module='.$tag->getModuleApiName();
            $this->addHeader('Content-Type', 'application/json');
            $tagJSON = [];
            $tagJSON['name'] = ''.$tag->getName();
            array_filter($tagJSON);
            $this->requestBody = json_encode(array_filter(['tags'=>[$tagJSON]]));
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();
            $responseDataArray = $responseInstance->getResponseJSON()['tags'];
            $responseData = $responseDataArray[0];
            $reponseDetails = $responseData['details'];
            self::setTagProperties($tag, $reponseDetails);
            $responseInstance->setData($tag);

            return $responseInstance;
       
    }

    public function addTags($record, $tagNames)
    {
        if (count($tagNames) > 10) {
            throw new ZCRMException(APIConstants::API_MAX_RECORD_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $tagname = '';
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            foreach ($tagNames as $tag) {
                $tagname .= $tag.',';
            }
            $this->urlPath = $record->getModuleApiName().'/'.$record->getEntityId().'/actions/add_tags?tag_names='.$tagname;
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();
            $responseDataArray = $responseInstance->getResponseJSON()['data'];
            $responseData = $responseDataArray[0];
            $reponseDetails = $responseData['details'];
            $tag = $record;
            $tag->setTags($reponseDetails['tags']);
            $responseInstance->setData($tag);

            return $responseInstance;
   
    }

    public function removeTags($record, $tagNames)
    {
        if (count($tagNames) > 10) {
            throw new ZCRMException(APIConstants::API_MAX_RECORD_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $tagname = '';
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            foreach ($tagNames as $tag) {
                $tagname .= $tag.',';
            }
            $this->urlPath = $record->getModuleApiName().'/'.$record->getEntityId().'/actions/remove_tags?tag_names='.$tagname;
            //Fire Request
            $responseInstance = APIRequest::getInstance($this)->getAPIResponse();
            $responseDataArray = $responseInstance->getResponseJSON()['data'];
            $responseData = $responseDataArray[0];
            $reponseDetails = $responseData['details'];
            $tag = $record;
            $tag->setTags($reponseDetails['tags']);
            $responseInstance->setData($tag);

            return $responseInstance;
      
    }

    public function addTagsToRecords($recordId, $tagNames)
    {
        if (count($tagNames) > 10) {
            throw new ZCRMException(APIConstants::API_MAX_RECORD_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }
        if (count($recordId) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }


            $tagname = '';
            $recordid = '';
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            foreach ($recordId as $id) {
                $recordid .= $id.',';
            }
            foreach ($tagNames as $tag) {
                $tagname .= $tag.',';
            }
            $this->urlPath = $this->module->getAPIName().'/actions/add_tags?ids='.$recordid.'&tag_names='.$tagname;
            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $addedTags = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            foreach ($responses as $entityResIns) {
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $tagDetails = $responseData['details'];
                    $addTag = ZCRMRecord::getInstance($this->module->getAPIName(), $tagDetails['id']);
                    $addTag->setTags($tagDetails['tags']);
                    array_push($addedTags, $addTag);
                    $entityResIns->setData($addTag);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($addedTags);

            return $bulkAPIResponse;
      
    }

    public function removeTagsFromRecords($recordId, $tagNames)
    {
        if (count($tagNames) > 10) {
            throw new ZCRMException(APIConstants::API_MAX_RECORD_TAGS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }
        if (count($recordId) > 100) {
            throw new ZCRMException(APIConstants::API_MAX_RECORDS_MSG, APIConstants::RESPONSECODE_BAD_REQUEST);
        }

        
            $tagname = '';
            $recordid = '';
            $this->requestMethod = APIConstants::REQUEST_METHOD_POST;
            foreach ($recordId as $id) {
                $recordid .= $id.',';
            }
            foreach ($tagNames as $tag) {
                $tagname .= $tag.',';
            }
            $this->urlPath = $this->module->getAPIName().'/actions/remove_tags?ids='.$recordid.'&tag_names='.$tagname;
            //Fire Request
            $bulkAPIResponse = APIRequest::getInstance($this)->getBulkAPIResponse();
            $removedTags = [];
            $responses = $bulkAPIResponse->getEntityResponses();
            foreach ($responses as $entityResIns) {
                if (APIConstants::STATUS_SUCCESS === $entityResIns->getStatus()) {
                    $responseData = $entityResIns->getResponseJSON();
                    $tagDetails = $responseData['details'];
                    $removeTag = ZCRMRecord::getInstance($this->module->getAPIName(), $tagDetails['id']);
                    $removeTag->setTags($tagDetails['tags']);
                    array_push($removedTags, $removeTag);
                    $entityResIns->setData($removeTag);
                } else {
                    $entityResIns->setData(null);
                }
            }
            $bulkAPIResponse->setData($removedTags);

            return $bulkAPIResponse;
       
    }

    public function setTagProperties($tagInstance, $tagDetails)
    {
        foreach ($tagDetails as $key=>$value) {
            if ('id' == $key) {
                $tagInstance->setId($value);
            } elseif ('name' == $key) {
                $tagInstance->setName($value);
            } elseif ('created_by' == $key) {
                $createdBy = ZCRMUser::getInstance($value['id'], $value['name']);
                $tagInstance->setCreatedBy($createdBy);
            } elseif ('modified_by' == $key) {
                $modifiedBy = ZCRMUser::getInstance($value['id'], $value['name']);
                $tagInstance->setModifiedBy($modifiedBy);
            } elseif ('created_time' == $key) {
                $tagInstance->setCreatedTime(''.$value);
            } elseif ('modified_time' == $key) {
                $tagInstance->setModifiedTime(''.$value);
            } elseif ('count' == $key) {
                $tagInstance->setCount($value);
            }
        }
    }

    public function getZCRMTagAsJSON($tag)
    {
        $recordJSON = [];
        if ($tag->getName() != null) {
            $recordJSON['name'] = ''.$tag->getName();
        }
        if ($tag->getId() != null) {
            $recordJSON['id'] = ''.$tag->getId();
        }

        return array_filter($recordJSON);
    }
}
