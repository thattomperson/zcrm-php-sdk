<?php

namespace Zoho\CRM\Library\Common;

use Zoho\CRM\Oauth\Client\ZohoOAuth;
use Zoho\CRM\Library\Setup\Restclient\ZCRMRestClient;
use Zoho\CRM\Oauth\Common\ZohoOAuthConstants;

class ZCRMConfigUtil
{
    private static $configProperties = [];

    public static function getInstance()
    {
        return new self();
    }

    public static function initialize($initializeOAuth, $configuration)
    {
        $mandatory_keys = [ZohoOAuthConstants::CLIENT_ID, ZohoOAuthConstants::CLIENT_SECRET, ZohoOAuthConstants::REDIRECT_URL, APIConstants::CURRENT_USER_EMAIL];
        if ($configuration == null) {
            $path = realpath(dirname(__FILE__).'/../../../../../resources/configuration.properties');
            $fileHandler = fopen($path, 'r');
            if (!$fileHandler) {
                return;
            }
            self::$configProperties = CommonUtil::getFileContentAsMap($fileHandler);
        } else {
            //check if user input contains all mandatory values
            foreach ($mandatory_keys as $key) {
                if (!array_key_exists($key, $configuration)) {
                    if ($key != APIConstants::CURRENT_USER_EMAIL) {
                        throw new ZohoOAuthException($key.' is mandatory');
                    } else {
                        if ($_SERVER[APIConstants::USER_EMAIL_ID] == null) {
                            throw new ZohoOAuthException($key.' is mandatory');
                        }
                    }
                } elseif (array_key_exists($key, $configuration) && $configuration[$key] == '') {
                    throw new ZohoOAuthException($key.' value is missing');
                }
            }
            self::setConfigValues($configuration);
        }
        if ($initializeOAuth) {
            ZohoOAuth::initializeWithOutInputStream($configuration);
        }
    }

    private static function setConfigValues($configuration)
    {
        $config_keys = [APIConstants::CURRENT_USER_EMAIL, ZohoOAuthConstants::SANDBOX, APIConstants::API_BASEURL,
            APIConstants::API_VERSION, APIConstants::APPLICATION_LOGFILE_PATH, ];

        if (!array_key_exists(ZohoOAuthConstants::SANDBOX, $configuration)) {
            self::$configProperties[ZohoOAuthConstants::SANDBOX] = 'false';
        }
        if (!array_key_exists(APIConstants::API_BASEURL, $configuration)) {
            self::$configProperties[APIConstants::API_BASEURL] = 'www.zohoapis.com';
        }
        if (!array_key_exists(APIConstants::API_VERSION, $configuration)) {
            self::$configProperties[APIConstants::API_VERSION] = 'v2';
        }
        foreach ($config_keys as $key) {
            if (array_key_exists($key, $configuration)) {
                self::$configProperties[$key] = $configuration[$key];
            }
        }
    }

    public static function loadConfigProperties($fileHandler)
    {
        $configMap = CommonUtil::getFileContentAsMap($fileHandler);
        foreach ($configMap as $key=>$value) {
            self::$configProperties[$key] = $value;
        }
    }

    public static function getConfigValue($key)
    {
        return isset(self::$configProperties[$key]) ? self::$configProperties[$key] : '';
    }

    public static function setConfigValue($key, $value)
    {
        self::$configProperties[$key] = $value;
    }

    public static function getAPIBaseUrl()
    {
        return self::getConfigValue('apiBaseUrl');
    }

    public static function getAPIVersion()
    {
        return self::getConfigValue('apiVersion');
    }

    public static function getAccessToken()
    {
        $currentUserEmail = ZCRMRestClient::getCurrentUserEmailID();

        if ($currentUserEmail == null && self::getConfigValue('currentUserEmail') == null) {
            throw new ZCRMException('Current user should either be set in ZCRMRestClient or in configuration.properties file');
        } elseif ($currentUserEmail == null) {
            $currentUserEmail = self::getConfigValue('currentUserEmail');
        }
        $oAuthCliIns = ZohoOAuth::getClientInstance();

        return $oAuthCliIns->getAccessToken($currentUserEmail);
    }

    public static function getAllConfigs()
    {
        return self::$configProperties;
    }
}
