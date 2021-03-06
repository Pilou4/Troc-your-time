<?php

namespace App\Entity;

class ProfileSearch
{
    /**
     * @var string|null
     */
    private $username;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $subCategory;

    /**
     * @var string|null
     */
    private $address;
    
    /**
     * @var integer|null
     */
    private $distance;

    /**
     * @var float|null
     */
    private $lng;

    /**
     * @var float|null
     */
    private $lat;

    /**
     * Get the value of distance
     *
     * @return  integer|null
     */ 
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set the value of distance
     *
     * @param  integer|null  $distance
     *
     * @return  self
     */ 
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get the value of lng
     *
     * @return  float|null
     */ 
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set the value of lng
     *
     * @param  float|null  $lng
     *
     * @return  self
     */ 
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get the value of lat
     *
     * @return  float|null
     */ 
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set the value of lat
     *
     * @param  float|null  $lat
     *
     * @return  self
     */ 
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return  string|null
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param  string|null  $address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of username
     *
     * @return  string|null
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string|null  $username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return  string|null
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  string|null  $category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of subCategory
     *
     * @return  string|null
     */ 
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * Set the value of subCategory
     *
     * @param  string|null  $subCategory
     *
     * @return  self
     */ 
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }
}