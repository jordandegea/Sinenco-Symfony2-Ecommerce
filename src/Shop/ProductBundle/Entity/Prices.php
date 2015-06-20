<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Prices
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\ProductBundle\Entity\PricesRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 * HasLifecycleCallbacks for add PreUpdate and PrePersist
 */
class Prices {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\CoreBundle\Entity\Currencies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $one_time;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $fee;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $monthly;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $quarterly;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $semiannually;

    /**
     * @var decimal
     * @ORM\Column(type="decimal",precision=10, scale=2, nullable=true)
     */
    private $annually;

    public function __toString() {
        return $this->id . "";
    }

    public function __construct() {
        
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
     * Set one_time
     *
     * @param string $oneTime
     * @return Prices
     */
    public function setOneTime($oneTime) {
        $this->one_time = $oneTime;

        return $this;
    }

    /**
     * Get one_time
     *
     * @return string 
     */
    public function getOneTime() {
        return $this->one_time;
    }

    /**
     * Set fee
     *
     * @param string $fee
     * @return Prices
     */
    public function setFee($fee) {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get fee
     *
     * @return string 
     */
    public function getFee() {
        return $this->fee;
    }

    /**
     * Set monthly
     *
     * @param string $monthly
     * @return Prices
     */
    public function setMonthly($monthly) {
        $this->monthly = $monthly;

        return $this;
    }

    /**
     * Get monthly
     *
     * @return string 
     */
    public function getMonthly() {
        return $this->monthly;
    }

    /**
     * Set quarterly
     *
     * @param string $quarterly
     * @return Prices
     */
    public function setQuarterly($quarterly) {
        $this->quarterly = $quarterly;

        return $this;
    }

    /**
     * Get quarterly
     *
     * @return string 
     */
    public function getQuarterly() {
        return $this->quarterly;
    }

    /**
     * Set semiannually
     *
     * @param string $semiannually
     * @return Prices
     */
    public function setSemiannually($semiannually) {
        $this->semiannually = $semiannually;

        return $this;
    }

    /**
     * Get semiannually
     *
     * @return string 
     */
    public function getSemiannually() {
        return $this->semiannually;
    }

    /**
     * Set annually
     *
     * @param string $annually
     * @return Prices
     */
    public function setAnnually($annually) {
        $this->annually = $annually;

        return $this;
    }

    /**
     * Get annually
     *
     * @return string 
     */
    public function getAnnually() {
        return $this->annually;
    }

    /**
     * Set currency
     *
     * @param \Shop\CoreBundle\Entity\Currencies $currency
     * @return Prices
     */
    public function setCurrency(\Shop\CoreBundle\Entity\Currencies $currency) {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \Shop\CoreBundle\Entity\Currencies 
     */
    public function getCurrency() {
        return $this->currency;
    }

}
