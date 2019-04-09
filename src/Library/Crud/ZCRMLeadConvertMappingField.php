<?php

namespace Zoho\CRM\Library\Crud;

class ZCRMLeadConvertMappingField
{
    /**
     * api name of the convertible field.
     *
     * @var string
     */
    private $apiName;
    /**
     * convertible field id.
     *
     * @var string
     */
    private $id;
    /**
     * diplay name of the field.
     *
     * @var string
     */
    private $fieldLabel;
    /**
     * required field.
     *
     * @var bool
     */
    private $required;

    /**
     * constructor to set the convertible field api name and id.
     *
     * @param string $apiName api name of the convertible field
     * @param string $id      convertible field id
     */
    private function __construct($apiName, $id)
    {
        $this->apiName = $apiName;
        $this->id = $id;
    }

    /**
     * method to get the convertible field instance.
     *
     * @param string $apiName api name of the convertible field
     * @param string $id      convertible field id
     *
     * @return ZCRMLeadConvertMappingField instance of the ZCRMLeadConvertMappingField class
     */
    public static function getInstance($apiName, $id)
    {
        return new self($apiName, $id);
    }

    /**
     * method to set the convertible field api name.
     *
     * @return string the convertible field api name
     */
    public function getApiName()
    {
        return $this->apiName;
    }

    /**
     * method to get the convertible field api name.
     *
     * @param string $apiName the convertible field api name
     */
    public function setApiName($apiName)
    {
        $this->apiName = $apiName;
    }

    /**
     * method to get the convertible field id.
     *
     * @return string convertible field id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * method to set the convertible field id.
     *
     * @param string $id convertible field id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * method to get the field label of the convertible field.
     *
     * @return string field the field label of the convertible field
     */
    public function getFieldLabel()
    {
        return $this->fieldLabel;
    }

    /**
     *  method to get the field label of the convertible field.
     *
     * @param string $fieldLabel the field label of the convertible field
     */
    public function setFieldLabel($fieldLabel)
    {
        $this->fieldLabel = $fieldLabel;
    }

    /**
     * method check whether the convertible field is required.
     *
     * @return bool true if required otherwise false
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * method to make the convertible field required.
     *
     * @param bool $visible true to make the convertible field required otherwise false
     */
    public function setRequired($visible)
    {
        $this->required = $visible;
    }
}
