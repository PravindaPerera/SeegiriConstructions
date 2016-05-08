<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReorderLevels
 */
class ReorderLevels
{
    /**
     * @var integer
     */
    private $reorderId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $reorderLevel;


    /**
     * Get reorderId
     *
     * @return integer 
     */
    public function getReorderId()
    {
        return $this->reorderId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ReorderLevels
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set reorderLevel
     *
     * @param float $reorderLevel
     * @return ReorderLevels
     */
    public function setReorderLevel($reorderLevel)
    {
        $this->reorderLevel = $reorderLevel;

        return $this;
    }

    /**
     * Get reorderLevel
     *
     * @return float 
     */
    public function getReorderLevel()
    {
        return $this->reorderLevel;
    }
}
