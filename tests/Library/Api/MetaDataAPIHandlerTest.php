<?php

namespace Zoho\CRM\Tests\Library\Api;

use Zoho\CRM\Tests\TestCase;

class MetaDataAPIHandlerTest extends TestCase
{
    public static $moduleList = [];
    public static $moduleNameVsApiName = [];
    public static $moduleNameList = [];
    private static $filePointer = null;
    private $currentModule = null;
    public static $moduleVsFieldMap = [];
    public static $moduleVsLayoutMap = [];


    public function testGetAllModules()
    {
        $this->markTestIncomplete();
        try {
            Main::incrementTotalCount();
            $startTime = microtime(true) * 1000;
            $endTime = 0;
            $instance = ZCRMRestClient::getInstance();
            $moduleArr = $instance->getAllModules()->getData();
            $endTime = microtime(true) * 1000;
            if ($moduleArr == null || count($moduleArr) <= 0) {
                throw new ZCRMException('No Modules Received');
            }
            foreach ($moduleArr as $module) {
                if ($module->getId() == null || $module->getModuleName() == null || $module->getAPIName() == null || $module->getSingularLabel() == null || $module->getPluralLabel() == null || !is_bool($module->isCustomModule()) || !is_bool($module->isCreatable()) || !is_bool($module->isConvertable()) || !is_bool($module->isEditable()) || !is_bool($module->isDeletable()) || !is_bool($module->isViewable()) || !is_bool($module->isApiSupported()) || !is_bool($module->isScoringSupported()) || !is_int($module->getBusinessCardFieldLimit()) || !is_int($module->getBusinessCardFieldLimit()) || !is_bool($module->isGlobalSearchSupported()) || !is_int($module->getSequenceNumber())) {
                    throw new ZCRMException('Some fields data is not fetched');
                } elseif ($module->getModuleName() != 'Feeds') {
                    if (count($module->getAllProfiles()) == 0) {
                        throw new ZCRMException('Profiles not set in the module object');
                    } else {
                        $profiles = $module->getAllProfiles();
                        foreach ($profiles as $profile) {
                            if ($profile->getId() == null || $profile->getName() == null) {
                                throw new ZCRMException('Profile Data is not set properly in the module object');
                            }
                        }
                    }
                }
                if ($module->isApiSupported()) {
                    self::$moduleList[$module->getAPIName()] = $module->getModuleName();
                    array_push(self::$moduleNameList, $module->getModuleName());
                    self::$moduleNameVsApiName[$module->getModuleName()] = $module->getAPIName();
                }
            }
            Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getAllModules', null, null, 'success', ($endTime - $startTime));
        } catch (ZCRMException $e) {
            $endTime = $endTime == 0 ? microtime(true) * 1000 : $endTime;
            Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getAllModules', $e->getMessage(), $e->getExceptionDetails(), 'failure', ($endTime - $startTime));
        }
    }

    public function testGetModule()
    {
        $this->markTestIncomplete();
        if (!count(self::$moduleList) > 0) {
            Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule', 'Invalid Response', 'Module List is null or empty', 'failure', 0);

            return;
        }
        foreach (self::$moduleList as $apiName=>$moduleName) {
            try {
                if ($moduleName == 'Feeds' || $moduleName == 'Home') {
                    continue;
                }
                self::setCurrentModule($apiName);
                Main::incrementTotalCount();
                $startTime = microtime(true) * 1000;
                $endTime = 0;
                $instance = ZCRMRestClient::getInstance();
                $moduleResponse = $instance->getModule($apiName);
                $zcrmModule = $moduleResponse->getData();
                $endTime = microtime(true) * 1000;
                if ($moduleResponse == null || $moduleResponse->getHttpStatusCode() != APIConstants::RESPONSECODE_OK || $zcrmModule == null) {
                    Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Response status code is not as expected('.$moduleResponse->getHttpStatusCode().')', 'failure', ($endTime - $startTime));
                    continue;
                }
                if ($zcrmModule->getId() == null || $zcrmModule->getModuleName() == null || $zcrmModule->getAPIName() == null || $zcrmModule->getSingularLabel() == null || $zcrmModule->getPluralLabel() == null || !is_bool($zcrmModule->isCustomModule()) || !is_bool($zcrmModule->isCreatable()) || !is_bool($zcrmModule->isConvertable()) || !is_bool($zcrmModule->isEditable()) || !is_bool($zcrmModule->isDeletable()) || !is_bool($zcrmModule->isViewable()) || !is_bool($zcrmModule->isApiSupported()) || !is_bool($zcrmModule->isScoringSupported()) || !is_int($zcrmModule->getBusinessCardFieldLimit()) || !is_int($zcrmModule->getSequenceNumber())) {
                    Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module fields data not fetched properly', 'failure', ($endTime - $startTime));
                    continue;
                }
                if ($zcrmModule->getRelatedLists() != null) {
                    $relatedLists = $zcrmModule->getRelatedLists();
                    foreach ($relatedLists as $relatedList) {
                        if ($relatedList->getApiName() == null || $relatedList->getDisplayLabel() == null || $relatedList->getId() == null || $relatedList->getType() == null || !is_bool($relatedList->isVisible())) {
                            Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module Related List details not fetched properly', 'failure', ($endTime - $startTime));
                            break;
                        }
                    }
                }
                $accessibleProfiles = $zcrmModule->getAllProfiles();
                foreach ($accessibleProfiles as $profile) {
                    if ($profile->getName() == null || $profile->getId() == null) {
                        Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module accessible profiles data not fetched properly', 'failure', ($endTime - $startTime));
                        break;
                    }
                }
                if (!is_array($zcrmModule->getProperties()) && !$moduleName == 'Approvals') {
                    Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module properties not fetched properly', 'failure', ($endTime - $startTime));
                    continue;
                }

                if (!is_array($zcrmModule->getBusinessCardFields()) || ($zcrmModule->getBusinessCardFieldLimit() > 0 && count($zcrmModule->getBusinessCardFields()) < 1)) {
                    Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module Business card fields not fetched properly', 'failure', ($endTime - $startTime));
                    continue;
                }

                if (!$zcrmModule->isCreatable()) {
                    continue;
                }

                $rawFieldArray = $zcrmModule->getFields();
                $fieldArray = [];
                foreach ($rawFieldArray as $field) {
                    if (!is_bool($field->isCustomField()) || !is_bool($field->isVisible()) || !is_bool($field->isReadOnly()) || !is_bool($field->isBusinessCardSupported()) || !is_string($field->getFieldLabel()) || !is_int($field->getId()) || !is_string($field->getApiName()) || !is_int($field->getLength()) || !is_string($field->getCreatedSource()) || !is_string($field->getDataType()) || (!(TestUtil::isActivityModule($moduleName) || $moduleName == 'Activities') && $field->getJsonType() == null)) {
                        Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module Field details not fetched properly', 'failure', ($endTime - $startTime));
                        break;
                    }
                    $fieldDetails = [];
                    $dataType = $field->getDataType();
                    $fieldPermissions = $field->getFieldLayoutPermissions();
                    if ('autonumber' == $dataType || !in_array('CREATE', $fieldPermissions) || $field->isReadOnly()) {
                        continue;
                    }
                    $fieldDetails['data_type'] = $dataType;
                    if ($dataType == 'lookup') {
                        $fieldDetails['lookup'] = $field->getLookupField();
                    } elseif ($dataType == 'picklist') {
                        $fieldDetails['pick_list_values'] = $field->getPickListFieldValues();
                    } elseif ($dataType == 'currency') {
                        $fieldDetails['precision'] = $field->getPrecision();
                        $fieldDetails['rounding_option'] = $field->getRoundingOption();
                    }
                    $fieldDetails['decimal_place'] = $field->getDecimalPlace();
                    $fieldDetails['length'] = $field->getLength() + 0;
                    $fieldDetails['field_label'] = $field->getFieldLabel();
                    $fieldArray[$field->getApiName()] = $fieldDetails;
                }
                self::$moduleVsFieldMap[$apiName] = $fieldArray;
                if ($zcrmModule->getLayouts() != null) {
                    $layouts = $zcrmModule->getLayouts();
                    $layoutVsFields = [];
                    foreach ($layouts as $layout) {
                        if (!is_string($layout->getName()) || !is_int($layout->getId()) || !is_int($layout->getStatus()) || !is_bool($layout->isVisible())) {
                            Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module Layout details not fetched properly', 'failure', ($endTime - $startTime));
                            break;
                        }
                        $layoutProfiles = $layout->getAccessibleProfiles();
                        foreach ($layoutProfiles as $profile) {
                            if (!is_string($profile->getName()) || !is_int($profile->getId())) {
                                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module layout accessible profiles data not fetched properly', 'failure', ($endTime - $startTime));
                                break;
                            }
                        }
                        $convertMapping = $layout->getConvertMapping();
                        if (count($convertMapping) > 0) {
                            $accMapIns = $convertMapping[APIConstants::ACCOUNTS];
                            $dealMapIns = $convertMapping[APIConstants::DEALS];
                            $conMapIns = $convertMapping[APIConstants::CONTACTS];
                            if (!is_string($accMapIns->getName()) || !is_int($accMapIns->getId())) {
                                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Lead convert mapping for Accounts not fetched properly', 'failure', ($endTime - $startTime));
                                break;
                            } elseif (!is_string($dealMapIns->getName()) || !is_int($dealMapIns->getId())) {
                                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Lead convert mapping for Deals not fetched properly', 'failure', ($endTime - $startTime));
                                break;
                            } elseif (!is_string($conMapIns->getName()) || !is_int($conMapIns->getId())) {
                                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Lead convert mapping for Contacts not fetched properly', 'failure', ($endTime - $startTime));
                                break;
                            }
                        }
                        $sections = $layout->getSections();
                        if ($sections == null) {
                            continue;
                        }
                        $layoutFieldArray = [];
                        foreach ($sections as $section) {
                            if (!is_string($section->getName()) || !is_int($section->getColumnCount()) || !is_int($section->getSequenceNumber()) || !is_string($section->getDisplayName())) {
                                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module layout section data not fetched properly', 'failure', ($endTime - $startTime));
                                break;
                            }

                            $fields = $section->getFields();
                            if ($fields == null) {
                                continue;
                            }
                            foreach ($fields as $field) {
                                if (!is_string($field->getApiName()) || !is_int($field->getId()) || !is_int($field->getSequenceNumber()) || !is_bool($field->isMandatory())) {
                                    Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$moduleName.')', 'Invalid Response', 'Module layout section fields data not fetched properly', 'failure', ($endTime - $startTime));
                                    break;
                                }
                                array_push($layoutFieldArray, $field->getApiName());
                            }
                        }
                        $layoutVsFields[$layout->getId()] = $layoutFieldArray;
                    }
                    self::$moduleVsLayoutMap[$apiName] = $layoutVsFields;
                }
                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$apiName.')', null, null, 'success', ($endTime - $startTime));
            } catch (ZCRMException $e) {
                $endTime = $endTime == 0 ? microtime(true) * 1000 : $endTime;
                Helper::writeToFile(self::$filePointer, Main::getCurrentCount(), 'ZCRMRestClient', 'getModule('.$apiName.')', $e->getMessage(), $e->getTraceAsString(), 'failure', ($endTime - $startTime));
            }
        }
    }

    /**
     * currentModule.
     *
     * @return string
     */
    public function getCurrentModule()
    {
        return $this->currentModule;
    }

    /**
     * currentModule.
     *
     * @param string $currentModule
     */
    public function setCurrentModule($currentModule)
    {
        $this->currentModule = $currentModule;
    }
}
