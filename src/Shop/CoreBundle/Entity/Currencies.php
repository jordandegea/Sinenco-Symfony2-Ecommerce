<?php

namespace Shop\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Currencies
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\CoreBundle\Entity\CurrenciesRepository")
 */
class Currencies {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="prefix", type="string", length=4)
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="suffix", type="string", length=5)
     */
    private $suffix;

    /**
     * @var integer
     * 
     * @ORM\Column(name="format", type="integer")
     */
    private $format;

    /**
     * @var decimal
     *
     * @ORM\Column(name="rate", type="decimal",precision=10, scale=2, nullable=false)
     */
    private $rate;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Currencies
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     * @return Currencies
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string 
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * Set suffix
     *
     * @param string $suffix
     * @return Currencies
     */
    public function setSuffix($suffix) {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return string 
     */
    public function getSuffix() {
        return $this->suffix;
    }

    /**
     * Set rate
     *
     * @param string $rate
     * @return Currencies
     */
    public function setRate($rate) {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string 
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * Set format
     *
     * @param integer $format
     * @return Currencies
     */
    public function setFormat($format) {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return integer 
     */
    public function getFormat() {
        return $this->format;
    }

    public function __toString() {
        return $this->code;
    }

}
