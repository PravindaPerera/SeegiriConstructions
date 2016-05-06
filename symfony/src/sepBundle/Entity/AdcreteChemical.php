<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdcreteChemical
 */
class AdcreteChemical
{
    /**
     * @var integer
     */
    private $id;

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
     * @param integer $openingBalance
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
     * @return AdcreteChemical
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
