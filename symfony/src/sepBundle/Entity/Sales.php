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
    private $invoiceNum;

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
     * @var float
     */
    private $salesAmount;

    /**
     * @var float
     */
    private $paymentReceived;

    /**
     * @var float
     */
    private $ndt;

    /**
     * @var float
     */
    private $vat;

    /**
     * @var float
     */
    private $svat;


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
     * Set invoiceNum
     *
     * @param string $invoiceNum
     * @return Sales
     */
    public function setInvoiceNum($invoiceNum)
    {
        $this->invoiceNum = $invoiceNum;

        return $this;
    }

    /**
     * Get invoiceNum
     *
     * @return string 
     */
    public function getInvoiceNum()
    {
        return $this->invoiceNum;
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
     * @param float $salesAmount
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
     * @return float 
     */
    public function getSalesAmount()
    {
        return $this->salesAmount;
    }

    /**
     * Set paymentReceived
     *
     * @param float $paymentReceived
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
     * @return float 
     */
    public function getPaymentReceived()
    {
        return $this->paymentReceived;
    }

    /**
     * Set ndt
     *
     * @param float $ndt
     * @return Sales
     */
    public function setNdt($ndt)
    {
        $this->ndt = $ndt;

        return $this;
    }

    /**
     * Get ndt
     *
     * @return float 
     */
    public function getNdt()
    {
        return $this->ndt;
    }

    /**
     * Set vat
     *
     * @param float $vat
     * @return Sales
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return float 
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set svat
     *
     * @param float $svat
     * @return Sales
     */
    public function setSvat($svat)
    {
        $this->svat = $svat;

        return $this;
    }

    /**
     * Get svat
     *
     * @return float 
     */
    public function getSvat()
    {
        return $this->svat;
    }
}
