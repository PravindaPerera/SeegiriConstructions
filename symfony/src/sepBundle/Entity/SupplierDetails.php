<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SupplierDetails
 */
class SupplierDetails
{
    /**
     * @var integer
     */
    private $supId;

    /**
     * @var string
     */
    private $supCompanyName;

    /**
     * @var string
     */
    private $supType;

    /**
     * @var integer
     */
    private $contactNumber;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \sepBundle\Entity\UserLogin
     */
    private $user;


    /**
     * Get supId
     *
     * @return integer 
     */
    public function getSupId()
    {
        return $this->supId;
    }

    /**
     * Set supCompanyName
     *
     * @param string $supCompanyName
     * @return SupplierDetails
     */
    public function setSupCompanyName($supCompanyName)
    {
        $this->supCompanyName = $supCompanyName;

        return $this;
    }

    /**
     * Get supCompanyName
     *
     * @return string 
     */
    public function getSupCompanyName()
    {
        return $this->supCompanyName;
    }

    /**
     * Set supType
     *
     * @param string $supType
     * @return SupplierDetails
     */
    public function setSupType($supType)
    {
        $this->supType = $supType;

        return $this;
    }

    /**
     * Get supType
     *
     * @return string 
     */
    public function getSupType()
    {
        return $this->supType;
    }

    /**
     * Set contactNumber
     *
     * @param integer $contactNumber
     * @return SupplierDetails
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return integer 
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return SupplierDetails
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SupplierDetails
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return SupplierDetails
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set user
     *
     * @param \sepBundle\Entity\UserLogin $user
     * @return SupplierDetails
     */
    public function setUser(\sepBundle\Entity\UserLogin $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \sepBundle\Entity\UserLogin 
     */
    public function getUser()
    {
        return $this->user;
    }
}
