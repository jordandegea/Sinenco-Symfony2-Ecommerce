<?php

namespace Shop\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table("useraddress")
 * @ORM\Entity(repositoryClass="Shop\UserBundle\Entity\UserAddressRepository")
 */
class UserAddress {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column( name="street_number", type="string", 
     *              length=10,nullable=true)
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column( name="route", type="string", 
     *              length=120,nullable=true)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column( name="additionalAddress", type="string", 
     *              length=120,nullable=true)
     */
    private $additionalAddress;

    /**
     * @var string
     *
     * @ORM\Column( name="city", type="string", 
     *              length=100,nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column( name="stateRegion", type="string", 
     *              length=100,nullable=true)
     */
    private $stateRegion;

    /**
     * @var string
     *
     * @ORM\Column( name="zip_code", type="string", 
     *              length=40,nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column( name="country", type="string", 
     *              length=100,nullable=true)
     */
    private $country;

    public function __toString() {

        $response = $this->streetNumber . ", " . $this->route . "\n";
        $response .= ($this->additionalAddress != "" ) ? $this->additionalAddress . "\n" : "";
        $response .= $this->zipCode . " " . $this->city . "\n";
        $response .= $this->stateRegion . " " . $this->country;

        return $response;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set stateRegion
     *
     * @param string $stateRegion
     * @return User
     */
    public function setStateRegion($stateRegion) {
        $this->stateRegion = $stateRegion;

        return $this;
    }

    /**
     * Get stateRegion
     *
     * @return string 
     */
    public function getStateRegion() {
        return $this->stateRegion;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return User
     */
    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode() {
        return $this->zipCode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     * @return UserAddress
     */
    public function setStreetNumber($streetNumber) {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string 
     */
    public function getStreetNumber() {
        return $this->streetNumber;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return UserAddress
     */
    public function setRoute($route) {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * Set additionalAddress
     *
     * @param string $additionalAddress
     * @return UserAddress
     */
    public function setAdditionalAddress($additionalAddress) {
        $this->additionalAddress = $additionalAddress;

        return $this;
    }

    /**
     * Get additionalAddress
     *
     * @return string 
     */
    public function getAdditionalAddress() {
        return $this->additionalAddress;
    }

}
