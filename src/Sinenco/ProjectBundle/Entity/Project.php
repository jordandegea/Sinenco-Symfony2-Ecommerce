<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Project {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    /**
     *  @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", cascade={"persist"})
     *  @ORM\JoinTable()
     * */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text")
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="priceMin", type="decimal")
     */
    private $priceMin;

    /**
     * @var string
     *
     * @ORM\Column(name="priceMax", type="decimal")
     */
    private $priceMax;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5)
     */
    private $currency;

    /**
     * @ORM\OneToOne(targetEntity="Sinenco\ProjectBundle\Entity\Document", cascade={"persist"})
     */
    private $specification; // Cahier des charges

    /**
     * @ORM\OneToOne(targetEntity="Sinenco\ProjectBundle\Entity\Document", cascade={"persist"})
     */
    private $proposition; // Proposition commerciale

    /**
     * @ORM\OneToOne(targetEntity="Sinenco\ProjectBundle\Entity\Estimate", cascade={"persist"})
     */
    private $estimate; // Devis

    /**
     * Get id
     *
     * @return integer
     */

    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Project
     */
    public function setSummary($summary) {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary() {
        return $this->summary;
    }

    /**
     * Set priceMin
     *
     * @param string $priceMin
     *
     * @return Project
     */
    public function setPriceMin($priceMin) {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * Get priceMin
     *
     * @return string
     */
    public function getPriceMin() {
        return $this->priceMin;
    }

    /**
     * Set priceMax
     *
     * @param string $priceMax
     *
     * @return Project
     */
    public function setPriceMax($priceMax) {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * Get priceMax
     *
     * @return string
     */
    public function getPriceMax() {
        return $this->priceMax;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Project
     */
    public function setCurrency($currency) {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

}
