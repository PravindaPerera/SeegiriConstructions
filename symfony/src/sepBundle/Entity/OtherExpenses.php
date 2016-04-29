<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OtherExpenses
 */
class OtherExpenses
{
    /**
     * @var integer
     */
    private $paymentId;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $paymentStatus;

    /**
     * @var \sepBundle\Entity\ExpenseDetails
     */
    private $expenseType;


    /**
     * Get paymentId
     *
     * @return integer 
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return OtherExpenses
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
     * Set date
     *
     * @param \DateTime $date
     * @return OtherExpenses
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
     * Set paymentStatus
     *
     * @param integer $paymentStatus
     * @return OtherExpenses
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return integer 
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set expenseType
     *
     * @param \sepBundle\Entity\ExpenseDetails $expenseType
     * @return OtherExpenses
     */
    public function setExpenseType(\sepBundle\Entity\ExpenseDetails $expenseType = null)
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    /**
     * Get expenseType
     *
     * @return \sepBundle\Entity\ExpenseDetails 
     */
    public function getExpenseType()
    {
        return $this->expenseType;
    }
}
