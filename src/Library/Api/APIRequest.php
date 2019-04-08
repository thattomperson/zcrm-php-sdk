<?php

namespace Zoho\CRM\Library\Api;

/**
 * This class is to construct the API requests and initiate the request.
 *
 * @author sumanth-3058
 */
class APIRequest
{
    private $url = null;
    private $requestParams = [];
    private $requestHeaders = [];
    private $requestBody;
    private $requestMethod;
    private $apiKey = null;
    private $response = null;
    private $reponseInfo = null;

    private function __construct($apiHandler)
    {
        self::constructAPIUrl();
        self::setUrl($this->url.$apiHandler->getUrlPath());
        if (substr($apiHandler->getUrlPath(), 0, 4) !== 'http') {
            self::setUrl('https://'.$this->url);
        }
        self::setRequestParams($apiHandler->getRequestParams());
        self::setRequestHeaders($apiHandler->getRequestHeaders());
        self::setRequestBody($apiHandler->getRequestBody());
        self::setRequestMethod($apiHandler->getRequestMethod());
        self::setApiKey($apiHandler->getApiKey());
    }

    public static function getInstance($apiHandler)
    {
        $instance = new self($apiHandler);

        return $instance;
    }

    /**
     * Method to construct the API Url.
     */
    public function constructAPIUrl()
    {
        $hitSandbox = ZCRMConfigUtil::getConfigValue('sandbox');
        $baseUrl = strcasecmp($hitSandbox, 'true') == 0 ? str_replace('www', 'sandbox', ZCRMConfigUtil::getAPIBaseUrl()) : ZCRMConfigUtil::getAPIBaseUrl();
        $this->url = $baseUrl.'/crm/'.ZCRMConfigUtil::getAPIVersion().'/';
        $this->url = str_replace(PHP_EOL, '', $this->url);
    }

    private function authenticateRequest()
    {
        try {
            $accessToken = (new ZCRMConfigUtil())->getAccessToken();
            $this->requestHeaders[APIConstants::AUTHORIZATION] = APIConstants::OAUTH_HEADER_PREFIX.$accessToken;
        } catch (ZCRMException $ex) {
            throw $ex;
        }
    }

    /**
     * initiate the request and get the API response.
     *
     * @return Instance of APIResponse
     */
    public function getAPIResponse()
    {
        try {
            $connector = ZohoHTTPConnector::getInstance();
            $connector->setUrl($this->url);
            self::authenticateRequest();
            $connector->setRequestHeadersMap($this->requestHeaders);
            $connector->setRequestParamsMap($this->requestParams);
            $connector->setRequestBody($this->requestBody);
            $connector->setRequestType($this->requestMethod);
            $connector->setApiKey($this->apiKey);
            $response = $connector->fireRequest();
            $this->response = $response[0];
            $this->responseInfo = $response[1];

            return new APIResponse($this->response, $this->responseInfo['http_code']);
        } catch (ZCRMException $e) {
            throw $e;
        }
    }

    /**
     * initiate the request and get the API response.
     *
     * @return instance of BulkAPIResponse
     */
    public function getBulkAPIResponse()
    {
        try {
            $connector = ZohoHTTPConnector::getInstance();
            $connector->setUrl($this->url);
            self::authenticateRequest();
            $connector->setRequestHeadersMap($this->requestHeaders);
            $connector->setRequestParamsMap($this->requestParams);
            $connector->setRequestBody($this->requestBody);
            $connector->setRequestType($this->requestMethod);
            $connector->setApiKey($this->apiKey);
            $connector->setBulkRequest(true);
            $response = $connector->fireRequest();
            $this->response = $response[0];
            $this->responseInfo = $response[1];

            return new BulkAPIResponse($this->response, $this->responseInfo['http_code']);
        } catch (ZCRMException $e) {
            throw $e;
        }
    }

    public function uploadFile($filePath)
    {
        try {
            $fileContent = file_get_contents($filePath);
            $filePathArray = explode('/', $filePath);
            $fileName = $filePathArray[count($filePathArray) - 1];
            if (function_exists('curl_file_create')) { // php 5.6+
                $cFile = curl_file_create($filePath);
            } else { //
                $cFile = '@'.realpath($filePath);
            }
            $post = ['file'=> $cFile];

            $connector = ZohoHTTPConnector::getInstance();
            $connector->setUrl($this->url);
            self::authenticateRequest();
            $connector->setRequestHeadersMap($this->requestHeaders);
            $connector->setRequestParamsMap($this->requestParams);
            $connector->setRequestBody($post);
            $connector->setRequestType($this->requestMethod);
            $connector->setApiKey($this->apiKey);
            $response = $connector->fireRequest();
            $this->response = $response[0];
            $this->responseInfo = $response[1];

            return new APIResponse($this->response, $this->responseInfo['http_code']);
        } catch (ZCRMException $e) {
            throw $e;
        }
    }

    public function uploadLinkAsAttachment($linkURL)
    {
        try {
            $post = ['attachmentUrl'=> $linkURL];

            $connector = ZohoHTTPConnector::getInstance();
            $connector->setUrl($this->url);
            self::authenticateRequest();
            $connector->setRequestHeadersMap($this->requestHeaders);
            $connector->setRequestBody($post);
            $connector->setRequestType($this->requestMethod);
            $connector->setApiKey($this->apiKey);
            $response = $connector->fireRequest();
            $this->response = $response[0];
            $this->responseInfo = $response[1];

            return new APIResponse($this->response, $this->responseInfo['http_code']);
        } catch (ZCRMException $e) {
            throw $e;
        }
    }

    public function downloadFile()
    {
        try {
            $connector = ZohoHTTPConnector::getInstance();
            $connector->setUrl($this->url);
            self::authenticateRequest();
            $connector->setRequestHeadersMap($this->requestHeaders);
            $connector->setRequestParamsMap($this->requestParams);
            $connector->setRequestType($this->requestMethod);
            $response = $connector->downloadFile();

            return (new FileAPIResponse())->setFileContent($response[0], $response[1]['http_code']);
        } catch (ZCRMException $e) {
            throw $e;
        }
    }

    /**
     * Get the request url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the request url.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the request parameters.
     *
     * @return array
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * Set the request parameters.
     *
     * @param array $requestParams
     */
    public function setRequestParams($requestParams)
    {
        $this->requestParams = $requestParams;
    }

    /**
     * Get the request headers.
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }

    /**
     * Set the request headers.
     *
     * @param array $requestHeaders
     */
    public function setRequestHeaders($requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
    }

    /**
     * Get the request body.
     *
     * @return JSON
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * Set the request body.
     *
     * @param JSON $requestBody
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Set the request method.
     *
     * @param string $requestMethod
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * Get the API Key used in the input json data(like 'modules', 'data','layouts',..etc).
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     *  Set the API Key used in the input json data(like 'modules', 'data','layouts',..etc).
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
