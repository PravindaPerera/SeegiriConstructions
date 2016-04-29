<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpenseDetails
 */
class ExpenseDetails
{
    /**
     * @var integer
     */
    private $expenseId;

    /**
     * @var string
     */
    private $expenseTitle;


    /**
     * Get expenseId
     *
     * @return integer 
     */
    public function getExpenseId()
    {
        return $this->expenseId;
    }

    /**
     * Set expenseTitle
     *
     * @param string $expenseTitle
     * @return ExpenseDetails
     */
    public function setExpenseTitle($expenseTitle)
    {
        $this->expenseTitle = $expenseTitle;

        return $this;
    }

    /**
     * Get expenseTitle
     *
     * @return string 
     */
    public function getExpenseTitle()
    {
        return $this->expenseTitle;
    }
}
