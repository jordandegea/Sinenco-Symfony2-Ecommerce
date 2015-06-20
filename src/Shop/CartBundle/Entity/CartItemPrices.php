<?php

namespace Shop\CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItemPrices
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\CartBundle\Entity\CartItemPricesRepository")
 */
class CartItemPrices {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    public static $listFunctionGetPrices = [
        ["function" => "getOneTime", "suffixPrice" => ""],
        ["function" => "getMonthly", "suffixPrice" => "calendar.month"],
        ["function" => "getQuarterly", "suffixPrice" => "calendar.quarter"],
        ["function" => "getSemiannually", "suffixPrice" => "calendar.semiannual"],
        ["function" => "getAnnually", "suffixPrice" => "calendar.year"]
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="one_time", type="integer")
     */
    private $oneTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthly", type="integer")
     */
    private $monthly;

    /**
     * @var integer
     *
     * @ORM\Column(name="quarterly", type="integer")
     */
    private $quarterly;

    /**
     * @var integer
     *
     * @ORM\Column(name="semiannually", type="integer")
     */
    private $semiannually;

    /**
     * @var integer
     *
     * @ORM\Column(name="annually", type="integer")
     */
    private $annually;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set oneTime
     *
     * @param integer $oneTime
     * @return CartItemPrices
     */
    public function setOneTime($oneTime) {
        $this->oneTime = $oneTime;

        return $this;
    }

    /**
     * Get oneTime
     *
     * @return integer 
     */
    public function getOneTime() {
        return $this->oneTime;
    }

    /**
     * Set monthly
     *
     * @param integer $monthly
     * @return CartItemPrices
     */
    public function setMonthly($monthly) {
        $this->monthly = $monthly;

        return $this;
    }

    /**
     * Get monthly
     *
     * @return integer 
     */
    public function getMonthly() {
        return $this->monthly;
    }

    /**
     * Set quarterly
     *
     * @param integer $quarterly
     * @return CartItemPrices
     */
    public function setQuarterly($quarterly) {
        $this->quarterly = $quarterly;

        return $this;
    }

    /**
     * Get quarterly
     *
     * @return integer 
     */
    public function getQuarterly() {
        return $this->quarterly;
    }

    /**
     * Set semiannually
     *
     * @param integer $semiannually
     * @return CartItemPrices
     */
    public function setSemiannually($semiannually) {
        $this->semiannually = $semiannually;

        return $this;
    }

    /**
     * Get semiannually
     *
     * @return integer 
     */
    public function getSemiannually() {
        return $this->semiannually;
    }

    /**
     * Set annually
     *
     * @param integer $annually
     * @return CartItemPrices
     */
    public function setAnnually($annually) {
        $this->annually = $annually;

        return $this;
    }

    /**
     * Get annually
     *
     * @return integer 
     */
    public function getAnnually() {
        return $this->annually;
    }

}
