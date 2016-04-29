<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chemical
 */
class Chemical
{
    /**
     * @var integer
     */
    private $chemId;

    /**
     * @var integer
     */
    private $openingBalance;

    /**
     * @var integer
     */
    private $clossingBalance;

    /**
     * @var integer
     */
    private $stockPurchased;

    /**
     * @var integer
     */
    private $stockUsed;

    /**
     * @var integer
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
     * Get chemId
     *
     * @return integer 
     */
    public function getChemId()
    {
        return $this->chemId;
    }

    /**
     * Set openingBalance
     *
     * @param integer $openingBalance
     * @return Chemical
     */
    public function setOpeningBalance($openingBalance)
    {
        $this->openingBalance = $openingBalance;

        return $this;
    }

    /**
     * Get openingBalance
     *
     * @return integer 
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }

    /**
     * Set clossingBalance
     *
     * @param integer $clossingBalance
     * @return Chemical
     */
    public function setClossingBalance($clossingBalance)
    {
        $this->clossingBalance = $clossingBalance;

        return $this;
    }

    /**
     * Get clossingBalance
     *
     * @return integer 
     */
    public function getClossingBalance()
    {
        return $this->clossingBalance;
    }

    /**
     * Set stockPurchased
     *
     * @param integer $stockPurchased
     * @return Chemical
     */
    public function setStockPurchased($stockPurchased)
    {
        $this->stockPurchased = $stockPurchased;

        return $this;
    }

    /**
     * Get stockPurchased
     *
     * @return integer 
     */
    public function getStockPurchased()
    {
        return $this->stockPurchased;
    }

    /**
     * Set stockUsed
     *
     * @param integer $stockUsed
     * @return Chemical
     */
    public function setStockUsed($stockUsed)
    {
        $this->stockUsed = $stockUsed;

        return $this;
    }

    /**
     * Get stockUsed
     *
     * @return integer 
     */
    public function getStockUsed()
    {
        return $this->stockUsed;
    }

    /**
     * Set netCost
     *
     * @param integer $netCost
     * @return Chemical
     */
    public function setNetCost($netCost)
    {
        $this->netCost = $netCost;

        return $this;
    }

    /**
     * Get netCost
     *
     * @return integer 
     */
    public function getNetCost()
    {
        return $this->netCost;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Chemical
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
     * @return Chemical
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
     * @return Chemical
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
