<?php

namespace Zoho\CRM\Library\Api\Response;

use Zoho\CRM\Library\Common\APIConstants;
use Zoho\CRM\Library\Exception\ZCRMException;

class APIResponse extends CommonAPIResponse
{
    /**
     * data of the api response.
     *
     * @var object
     */
    private $data = null;
    /**
     * response status of the api.
     *
     * @var string
     */
    private $status = null;

    /**
     * constructor to set the http response , http code and apiname.
     *
     * @param string $httpResponse   the http response
     * @param int    $httpStatusCode status code of the response
     * @param string $apiName        module api name
     */
    public function __construct($httpResponse, $httpStatusCode, $apiName = null)
    {
        parent::__construct($httpResponse, $httpStatusCode, $apiName);
    }

    /**
     * method to set the data of the class object.
     *
     * @param object $data data to be set for the object
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * method to get the data of the class object.
     *
     * @return object data of the object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * method to Get the response status.
     *
     * @return string the response status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *  method to Set the response status.
     *
     * @param string $status the response status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     *
     * @see CommonAPIResponse::handleForFaultyResponses()
     */
    public function handleForFaultyResponses()
    {
        $statusCode = self::getHttpStatusCode();
        if (in_array($statusCode, APIExceptionHandler::getFaultyResponseCodes())) {
            if ($statusCode == APIConstants::RESPONSECODE_NO_CONTENT) {
                $exception = new ZCRMException(APIConstants::INVALID_DATA.'-'.APIConstants::INVALID_ID_MSG, $statusCode);
                $exception->setExceptionCode('No Content');

                throw $exception;
            } else {
                $responseJSON = $this->getResponseJSON();
                $exception = new ZCRMException($responseJSON['message'], $statusCode);
                $exception->setExceptionCode($responseJSON['code']);
                $exception->setExceptionDetails($responseJSON['details']);

                throw $exception;
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see CommonAPIResponse::processResponseData()
     */
    public function processResponseData()
    {
        $responseJSON = $this->getResponseJSON();
        if ($responseJSON == null) {
            return;
        }
        if (array_key_exists('data', $responseJSON)) {
            $responseJSON = $responseJSON['data'][0];
        }
        if (array_key_exists('tags', $responseJSON)) {
            $responseJSON = $responseJSON['tags'][0];
        } elseif (array_key_exists('users', $responseJSON)) {
            $responseJSON = $responseJSON['users'][0];
        } elseif (array_key_exists('modules', $responseJSON)) {
            $responseJSON = $responseJSON['modules'];
        } elseif (array_key_exists('custom_views', $responseJSON)) {
            $responseJSON = $responseJSON['custom_views'];
        }
        if (isset($responseJSON['status']) && $responseJSON['status'] == APIConstants::STATUS_ERROR) {
            $exception = new ZCRMException($responseJSON['message'], self::getHttpStatusCode());
            $exception->setExceptionCode($responseJSON['code']);
            $exception->setExceptionDetails($responseJSON['details']);

            throw $exception;
        } elseif (isset($responseJSON['status']) && $responseJSON['status'] == APIConstants::STATUS_SUCCESS) {
            self::setCode($responseJSON['code']);
            self::setStatus($responseJSON['status']);
            self::setMessage($responseJSON['message']);
            self::setDetails($responseJSON['details']);
        }
    }
}
