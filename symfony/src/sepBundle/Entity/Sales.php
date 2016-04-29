<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sales
 */
class Sales
{
    /**
     * @var integer
     */
    private $salesId;

    /**
     * @var string
     */
    private $customerName;

    /**
     * @var integer
     */
    private $contactNum;

    /**
     * @var \DateTime
     */
    private $salesDate;

    /**
     * @var string
     */
    private $month;

    /**
     * @var string
     */
    private $year;

    /**
     * @var integer
     */
    private $salesAmount;

    /**
     * @var integer
     */
    private $paymentReceived;


    /**
     * Get salesId
     *
     * @return integer 
     */
    public function getSalesId()
    {
        return $this->salesId;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return Sales
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
     * @param integer $contactNum
     * @return Sales
     */
    public function setContactNum($contactNum)
    {
        $this->contactNum = $contactNum;

        return $this;
    }

    /**
     * Get contactNum
     *
     * @return integer 
     */
    public function getContactNum()
    {
        return $this->contactNum;
    }

    /**
     * Set salesDate
     *
     * @param \DateTime $salesDate
     * @return Sales
     */
    public function setSalesDate($salesDate)
    {
        $this->salesDate = $salesDate;

        return $this;
    }

    /**
     * Get salesDate
     *
     * @return \DateTime 
     */
    public function getSalesDate()
    {
        return $this->salesDate;
    }

    /**
     * Set month
     *
     * @param string $month
     * @return Sales
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Sales
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set salesAmount
     *
     * @param integer $salesAmount
     * @return Sales
     */
    public function setSalesAmount($salesAmount)
    {
        $this->salesAmount = $salesAmount;

        return $this;
    }

    /**
     * Get salesAmount
     *
     * @return integer 
     */
    public function getSalesAmount()
    {
        return $this->salesAmount;
    }

    /**
     * Set paymentReceived
     *
     * @param integer $paymentReceived
     * @return Sales
     */
    public function setPaymentReceived($paymentReceived)
    {
        $this->paymentReceived = $paymentReceived;

        return $this;
    }

    /**
     * Get paymentReceived
     *
     * @return integer 
     */
    public function getPaymentReceived()
    {
        return $this->paymentReceived;
    }
}
