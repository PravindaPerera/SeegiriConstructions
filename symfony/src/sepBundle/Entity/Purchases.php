<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchases
 */
class Purchases
{
    /**
     * @var integer
     */
    private $purchaseId;

    /**
     * @var string
     */
    private $deliveryOrderId;

    /**
     * @var string
     */
    private $material;

    /**
     * @var string
     */
    private $supplierName;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var integer
     */
    private $cost;


    /**
     * Get purchaseId
     *
     * @return integer 
     */
    public function getPurchaseId()
    {
        return $this->purchaseId;
    }

    /**
     * Set deliveryOrderId
     *
     * @param string $deliveryOrderId
     * @return Purchases
     */
    public function setDeliveryOrderId($deliveryOrderId)
    {
        $this->deliveryOrderId = $deliveryOrderId;

        return $this;
    }

    /**
     * Get deliveryOrderId
     *
     * @return string 
     */
    public function getDeliveryOrderId()
    {
        return $this->deliveryOrderId;
    }

    /**
     * Set material
     *
     * @param string $material
     * @return Purchases
     */
    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return string 
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set supplierName
     *
     * @param string $supplierName
     * @return Purchases
     */
    public function setSupplierName($supplierName)
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    /**
     * Get supplierName
     *
     * @return string 
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Purchases
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
     * Set amount
     *
     * @param integer $amount
     * @return Purchases
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
     * Set cost
     *
     * @param integer $cost
     * @return Purchases
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer 
     */
    public function getCost()
    {
        return $this->cost;
    }
}
