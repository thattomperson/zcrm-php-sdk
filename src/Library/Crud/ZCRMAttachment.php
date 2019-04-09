<?php

namespace Zoho\CRM\Library\Crud;

class ZCRMAttachment
{
    /**
     * Attachment id.
     *
     * @var string
     */
    private $id = null;
    /**
     * file name of the attachment.
     *
     * @var string
     */
    private $fileName = null;
    /**
     * file type of the attachment.
     *
     * @var string
     */
    private $fileType = null;
    /**
     * size of the attachment.
     *
     * @var string
     */
    private $size = null;
    /**
     * owner of the attachment.
     *
     * @var ZCRMUser
     */
    private $owner = null;
    /**
     * the user who created the attachment.
     *
     * @var ZCRMUser
     */
    private $createdBy = null;
    /**
     * creation time of the attachment.
     *
     * @var string
     */
    private $createdTime = null;
    /**
     * the user  who modified the attachment.
     *
     * @var ZCRMUser
     */
    private $modifiedBy = null;
    /**
     * modification time of the attachment.
     *
     * @var string
     */
    private $modifiedTime = null;
    /**
     * the record that consists this attachment.
     *
     * @var ZCRMRecord
     */
    private $parentRecord = null;
    /**
     * the module that consists this attachment.
     *
     * @var ZCRMModule
     */
    private $parentModule = null;
    /**
     * the type of attachment.
     *
     * @var string
     */
    private $attachmentType = null;
    /**
     * the record name that consists this attachment.
     *
     * @var string
     */
    private $parentName = null;
    /**
     * the record id that consists this attachment.
     *
     * @var string
     */
    private $parentId = null;

    /**
     * constructor to set the parent record and the attachment id.
     *
     * @param ZCRMRecord $parentRecord
     * @param string     $attachmentId
     */
    private function __construct($parentRecord, $attachmentId)
    {
        $this->parentRecord = $parentRecord;
        $this->id = $attachmentId;
    }

    /**
     * Method to get the instance of the ZCRMAttachment class.
     *
     * @param ZCRMRecord $parentRecord
     * @param string     $attachmentId the attachment id
     *
     * @return ZCRMAttachment-instance
     */
    public static function getInstance($parentRecord, $attachmentId = null)
    {
        return new self($parentRecord, $attachmentId);
    }

    /**
     * Method to get the Attachment id of the attachment.
     *
     * @return string the attachment id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Method to set the Attachment id of the attachment.
     *
     * @param string $id the attachment id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Method to get the filename of the attachment.
     *
     * @return string file name of the attachment
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Method to set the filename of the attachment.
     *
     * @param string $fileName file name of the attachment
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Method to get the filetype of the attachment.
     *
     * @return string filetype of the attachment
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Method to set the filetype of the attachment.
     *
     * @param string $fileType filetype of the attachment
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * Method to set the size of the attachment.
     *
     * @return string size of the attachment
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Method to get the size of the attachment.
     *
     * @param string $size size of the attachment
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Method to get the parent Record of the attachment.
     *
     * @return ZCRMRecord the parent record
     */
    public function getParentRecord()
    {
        return $this->parentRecord;
    }

    /**
     * Method to set the parent Record of the attachment.
     *
     * @param ZCRMRecord $parentRecord the parent record
     */
    public function setParentRecord($parentRecord)
    {
        $this->parentRecord = $parentRecord;
    }

    /**
     * Method to get the owner of the attachment.
     *
     * @return ZCRMUser owner of the attachment
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Method to set the owner of the attachment.
     *
     * @param ZCRMUser $owner owner of the attachment
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Method to get the creator of that attachment.
     *
     * @return ZCRMUser user who created the attachment
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Method to set the creator of that attachment.
     *
     * @param ZCRMUser $createdBy user who created the attachment
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Method to get the creation time of the attachment.
     *
     * @return string creation time in ISO 8601 format
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Method to set the creation time of the attachment.
     *
     * @param string $createdTime creation time in ISO 8601 format
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * Method to get the user who modified the attachment.
     *
     * @return ZCRMUser user who modified the attachment
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Method to set the user who modified the attachment.
     *
     * @param ZCRMUser $modifiedBy user who modified the attachment
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Method to get the modification time of the attachment.
     *
     * @return string modification time in ISO 8601 format
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Method to set the modification time of the attachment.
     *
     * @param string $modifiedTime modification time in ISO 8601 format
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;
    }

    /**
     * Method to get the parent module of the attachment.
     *
     * @return ZCRMModule parent module
     */
    public function getParentModule()
    {
        return $this->parentModule;
    }

    /**
     * Method to set the parent module of the attachment.
     *
     * @param ZCRMModule $parentModule instance of the ZCRMModule class
     */
    public function setParentModule($parentModule)
    {
        $this->parentModule = $parentModule;
    }

    /**
     * method to get the attachment Type of the attachment.
     *
     * @return string the attachment type
     */
    public function getAttachmentType()
    {
        return $this->attachmentType;
    }

    /**
     * method to set the attachment Type of the attachment.
     *
     * @param string $attachmentType the attachment type
     */
    public function setAttachmentType($attachmentType)
    {
        $this->attachmentType = $attachmentType;
    }

    /**
     * Method to get the parent record name of the attachment.
     *
     * @return string parent record name of the attachment
     */
    public function getParentName()
    {
        return $this->parentName;
    }

    /**
     * Method to set the parent record name of the attachment.
     *
     * @param string $parentName the parent record name of the attachment
     */
    public function setParentName($parentName)
    {
        $this->parentName = $parentName;
    }

    /**
     * Method to get the parent record id of the attachment.
     *
     * @return string parent record id of the attachment
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Method to set the parent record id of the attachment.
     *
     * @param string $parentId parent record id of the attachment
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Method to download the attachment.
     *
     * @return FileAPIResponse instance of the FileAPIResponse class which holds the response.
     */
    public function downloadFile()
    {
        return ZCRMModuleRelation::getInstance($this->parentRecord, 'Attachments')->downloadAttachment($this->id);
    }
}
