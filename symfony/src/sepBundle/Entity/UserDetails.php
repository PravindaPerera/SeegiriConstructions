<?php

namespace sepBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserDetails
 */
class UserDetails
{
    /**
     * @var integer
     */
    private $detailId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var integer
     */
    private $contactNumber;

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
    private $u;


    /**
     * Get detailId
     *
     * @return integer 
     */
    public function getDetailId()
    {
        return $this->detailId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return UserDetails
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return UserDetails
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set contactNumber
     *
     * @param integer $contactNumber
     * @return UserDetails
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
     * Set name
     *
     * @param string $name
     * @return UserDetails
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
     * @return UserDetails
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
     * Set u
     *
     * @param \sepBundle\Entity\UserLogin $u
     * @return UserDetails
     */
    public function setU(\sepBundle\Entity\UserLogin $u = null)
    {
        $this->u = $u;

        return $this;
    }

    /**
     * Get u
     *
     * @return \sepBundle\Entity\UserLogin 
     */
    public function getU()
    {
        return $this->u;
    }
}
