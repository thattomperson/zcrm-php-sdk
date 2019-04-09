<?php

namespace Zoho\CRM\Library\Crud;

class ZCRMEventParticipant
{
    /**
     * the email id of the participant.
     *
     * @var string
     */
    private $email = null;
    /**
     * the name of the event participant.
     *
     * @var string
     */
    private $name = null;
    /**
     * the participant id.
     *
     * @var string
     */
    private $id = null;
    /**
     * type of the participant.
     *
     * @var string
     */
    private $type = null;
    /**
     * used to check if participant is invited.
     *
     * @var bool
     */
    private $isInvited = null;
    /**
     * status of the participant.
     *
     * @var string
     */
    private $status = null;

    /**
     * constructor to set the participant type and id.
     *
     * @param string $type participant type
     * @param string $id   record id
     */
    private function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Method to get the instance of ZCRMEvent participant.
     *
     * @param string $type participant type
     * @param string $id   record id
     *
     * @return ZCRMEventParticipant the instance of ZCRMEventParticipant class
     */
    public static function getInstance($type, $id)
    {
        return new self($type, $id);
    }

    /**
     * Method to get the Email id of the participant.
     *
     * @return string participant email id
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Method to set the Email id of the participant.
     *
     * @param string $email participant email id
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Method to get the name of the participant.
     *
     * @return string participant name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Method to set the name of the participant.
     *
     * @param string $name participant name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Method to get the record id of the participant.
     *
     * @return string record id of the participant
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Method to set the record id of the participant.
     *
     * @param string $id record id of the participant
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Method to get the participant type.
     *
     * @return string participant type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Method to set the participant type.
     *
     * @param string $type the participant type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Method to check whether the participant is invited.
     *
     * @return bool true if invited
     */
    public function isInvited()
    {
        return $this->isInvited;
    }

    /**
     * Method to set the invite status of the participant.
     *
     * @param bool $isInvited true if invited
     */
    public function setInvited($isInvited)
    {
        $this->isInvited = $isInvited;
    }

    /**
     * Method to get the status of the participant.
     *
     * @return string status of the participant
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *  Method to set the status of the participant.
     *
     * @param string $status status of the participant
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
