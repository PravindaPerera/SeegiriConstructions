<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 */
class Orders
{
    /**
     * @var integer
     */
    private $orderId;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var integer
     */
    private $purchasedAmount;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \DateTime
     */
    private $purDate;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \sepBundle\Entity\UserLogin
     */
    private $sup;


    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Orders
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set purchasedAmount
     *
     * @param integer $purchasedAmount
     * @return Orders
     */
    public function setPurchasedAmount($purchasedAmount)
    {
        $this->purchasedAmount = $purchasedAmount;

        return $this;
    }

    /**
     * Get purchasedAmount
     *
     * @return integer 
     */
    public function getPurchasedAmount()
    {
        return $this->purchasedAmount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Orders
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set purDate
     *
     * @param \DateTime $purDate
     * @return Orders
     */
    public function setPurDate($purDate)
    {
        $this->purDate = $purDate;

        return $this;
    }

    /**
     * Get purDate
     *
     * @return \DateTime 
     */
    public function getPurDate()
    {
        return $this->purDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sup
     *
     * @param \sepBundle\Entity\UserLogin $sup
     * @return Orders
     */
    public function setSup(\sepBundle\Entity\UserLogin $sup = null)
    {
        $this->sup = $sup;

        return $this;
    }

    /**
     * Get sup
     *
     * @return \sepBundle\Entity\UserLogin 
     */
    public function getSup()
    {
        return $this->sup;
    }
}
