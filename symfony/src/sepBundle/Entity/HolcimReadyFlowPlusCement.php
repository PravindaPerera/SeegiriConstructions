<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HolcimReadyFlowPlusCement
 */
class HolcimReadyFlowPlusCement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $openingBalance;

    /**
     * @var float
     */
    private $clossingBalance;

    /**
     * @var float
     */
    private $stockPurchased;

    /**
     * @var float
     */
    private $stockUsed;

    /**
     * @var float
     */
    private $netCost;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $month;

    /**
     * @var string
     */
    private $year;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set openingBalance
     *
     * @param float $openingBalance
     * @return HolcimReadyFlowPlusCement
     */
    public function setOpeningBalance($openingBalance)
    {
        $this->openingBalance = $openingBalance;

        return $this;
    }

    /**
     * Get openingBalance
     *
     * @return float 
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }

    /**
     * Set clossingBalance
     *
     * @param float $clossingBalance
     * @return HolcimReadyFlowPlusCement
     */
    public function setClossingBalance($clossingBalance)
    {
        $this->clossingBalance = $clossingBalance;

        return $this;
    }

    /**
     * Get clossingBalance
     *
     * @return float 
     */
    public function getClossingBalance()
    {
        return $this->clossingBalance;
    }

    /**
     * Set stockPurchased
     *
     * @param float $stockPurchased
     * @return HolcimReadyFlowPlusCement
     */
    public function setStockPurchased($stockPurchased)
    {
        $this->stockPurchased = $stockPurchased;

        return $this;
    }

    /**
     * Get stockPurchased
     *
     * @return float 
     */
    public function getStockPurchased()
    {
        return $this->stockPurchased;
    }

    /**
     * Set stockUsed
     *
     * @param float $stockUsed
     * @return HolcimReadyFlowPlusCement
     */
    public function setStockUsed($stockUsed)
    {
        $this->stockUsed = $stockUsed;

        return $this;
    }

    /**
     * Get stockUsed
     *
     * @return float 
     */
    public function getStockUsed()
    {
        return $this->stockUsed;
    }

    /**
     * Set netCost
     *
     * @param float $netCost
     * @return HolcimReadyFlowPlusCement
     */
    public function setNetCost($netCost)
    {
        $this->netCost = $netCost;

        return $this;
    }

    /**
     * Get netCost
     *
     * @return float 
     */
    public function getNetCost()
    {
        return $this->netCost;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return HolcimReadyFlowPlusCement
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
     * Set month
     *
     * @param string $month
     * @return HolcimReadyFlowPlusCement
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
     * @return HolcimReadyFlowPlusCement
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
}
