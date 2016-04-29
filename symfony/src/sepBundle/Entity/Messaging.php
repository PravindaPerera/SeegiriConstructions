<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messaging
 */
class Messaging
{
    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var string
     */
    private $messageHeaderByCompany;

    /**
     * @var string
     */
    private $messageByCompany;

    /**
     * @var string
     */
    private $messageHeaderBySupplier;

    /**
     * @var string
     */
    private $messageBySupplier;

    /**
     * @var \DateTime
     */
    private $sendTime;

    /**
     * @var \DateTime
     */
    private $replyTime;

    /**
     * @var integer
     */
    private $messageStatus;

    /**
     * @var \sepBundle\Entity\UserLogin
     */
    private $supplier;


    /**
     * Get messageId
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set messageHeaderByCompany
     *
     * @param string $messageHeaderByCompany
     * @return Messaging
     */
    public function setMessageHeaderByCompany($messageHeaderByCompany)
    {
        $this->messageHeaderByCompany = $messageHeaderByCompany;

        return $this;
    }

    /**
     * Get messageHeaderByCompany
     *
     * @return string 
     */
    public function getMessageHeaderByCompany()
    {
        return $this->messageHeaderByCompany;
    }

    /**
     * Set messageByCompany
     *
     * @param string $messageByCompany
     * @return Messaging
     */
    public function setMessageByCompany($messageByCompany)
    {
        $this->messageByCompany = $messageByCompany;

        return $this;
    }

    /**
     * Get messageByCompany
     *
     * @return string 
     */
    public function getMessageByCompany()
    {
        return $this->messageByCompany;
    }

    /**
     * Set messageHeaderBySupplier
     *
     * @param string $messageHeaderBySupplier
     * @return Messaging
     */
    public function setMessageHeaderBySupplier($messageHeaderBySupplier)
    {
        $this->messageHeaderBySupplier = $messageHeaderBySupplier;

        return $this;
    }

    /**
     * Get messageHeaderBySupplier
     *
     * @return string 
     */
    public function getMessageHeaderBySupplier()
    {
        return $this->messageHeaderBySupplier;
    }

    /**
     * Set messageBySupplier
     *
     * @param string $messageBySupplier
     * @return Messaging
     */
    public function setMessageBySupplier($messageBySupplier)
    {
        $this->messageBySupplier = $messageBySupplier;

        return $this;
    }

    /**
     * Get messageBySupplier
     *
     * @return string 
     */
    public function getMessageBySupplier()
    {
        return $this->messageBySupplier;
    }

    /**
     * Set sendTime
     *
     * @param \DateTime $sendTime
     * @return Messaging
     */
    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;

        return $this;
    }

    /**
     * Get sendTime
     *
     * @return \DateTime 
     */
    public function getSendTime()
    {
        return $this->sendTime;
    }

    /**
     * Set replyTime
     *
     * @param \DateTime $replyTime
     * @return Messaging
     */
    public function setReplyTime($replyTime)
    {
        $this->replyTime = $replyTime;

        return $this;
    }

    /**
     * Get replyTime
     *
     * @return \DateTime 
     */
    public function getReplyTime()
    {
        return $this->replyTime;
    }

    /**
     * Set messageStatus
     *
     * @param integer $messageStatus
     * @return Messaging
     */
    public function setMessageStatus($messageStatus)
    {
        $this->messageStatus = $messageStatus;

        return $this;
    }

    /**
     * Get messageStatus
     *
     * @return integer 
     */
    public function getMessageStatus()
    {
        return $this->messageStatus;
    }

    /**
     * Set supplier
     *
     * @param \sepBundle\Entity\UserLogin $supplier
     * @return Messaging
     */
    public function setSupplier(\sepBundle\Entity\UserLogin $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return \sepBundle\Entity\UserLogin 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
}
