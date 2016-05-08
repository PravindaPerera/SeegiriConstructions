<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalesOrders
 */
class SalesOrders
{
    /**
     * @var integer
     */
    private $salesOrderId;

    /**
     * @var string
     */
    private $customerName;

    /**
     * @var string
     */
    private $contactNum;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $grade;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var \DateTime
     */
    private $salesOrderDate;

    /**
     * @var integer
     */
    private $status;


    /**
     * Get salesOrderId
     *
     * @return integer 
     */
    public function getSalesOrderId()
    {
        return $this->salesOrderId;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return SalesOrders
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set contactNum
     *
     * @param string $contactNum
     * @return SalesOrders
     */
    public function setContactNum($contactNum)
    {
        $this->contactNum = $contactNum;

        return $this;
    }

    /**
     * Get contactNum
     *
     * @return string 
     */
    public function getContactNum()
    {
        return $this->contactNum;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return SalesOrders
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return SalesOrders
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return SalesOrders
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set salesOrderDate
     *
     * @param \DateTime $salesOrderDate
     * @return SalesOrders
     */
    public function setSalesOrderDate($salesOrderDate)
    {
        $this->salesOrderDate = $salesOrderDate;

        return $this;
    }

    /**
     * Get salesOrderDate
     *
     * @return \DateTime 
     */
    public function getSalesOrderDate()
    {
        return $this->salesOrderDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return SalesOrders
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
}
