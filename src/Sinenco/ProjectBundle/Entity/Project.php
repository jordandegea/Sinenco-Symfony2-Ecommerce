<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sinenco\ProjectBundle\Entity\Document,
    Sinenco\ProjectBundle\Entity\Estimate;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Project {

    const STATE_WAITING_DEV  = 0;
    const STATE_WAITING_USER  = 1;
    const STATE_REFUSED  = 2;
    const STATE_ACTIVE = 3;
    
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
     * @ORM\Column(name="state", type="smallint")
     */
    private $state;

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
     * @ORM\Column(name="priceMin", type="decimal", precision=10, scale=2)
     */
    private $priceMin;

    /**
     * @var string
     *
     * @ORM\Column(name="priceMax", type="decimal", precision=10, scale=2)
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $specification; // Cahier des charges

    /**
     * @ORM\OneToOne(targetEntity="Sinenco\ProjectBundle\Entity\Document", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $proposition; // Proposition commerciale

    /**
     * @ORM\OneToOne(targetEntity="Sinenco\ProjectBundle\Entity\Estimate", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $estimate; // Devis

    /**
     * @ORM\PrePersist
     */

    public function onPrePersist() {
        if ($this->currency == null) {
            $this->currency = "EUR";
        }
        if ($this->priceMin == null) {
            $this->priceMin = 0.0;
        }
        if ($this->reference == null) {
            $this->reference = "";
        }
        if ($this->priceMax == null) {
            $this->priceMax = 0.0;
        }
        if ($this->state == null) {
            $this->state = self::STATE_WAITING_DEV;
        }
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


    /**
     * Set state
     *
     * @param string $state
     *
     * @return Project
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Project
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     *
     * @return Project
     */
    public function setUser(\Sinenco\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set specification
     *
     * @param \Sinenco\ProjectBundle\Entity\Document $specification
     *
     * @return Project
     */
    public function setSpecification(\Sinenco\ProjectBundle\Entity\Document $specification = null)
    {
        $this->specification = $specification;

        return $this;
    }

    /**
     * Get specification
     *
     * @return \Sinenco\ProjectBundle\Entity\Document
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * Set proposition
     *
     * @param \Sinenco\ProjectBundle\Entity\Document $proposition
     *
     * @return Project
     */
    public function setProposition(\Sinenco\ProjectBundle\Entity\Document $proposition = null)
    {
        $this->proposition = $proposition;

        return $this;
    }

    /**
     * Get proposition
     *
     * @return \Sinenco\ProjectBundle\Entity\Document
     */
    public function getProposition()
    {
        return $this->proposition;
    }

    /**
     * Set estimate
     *
     * @param \Sinenco\ProjectBundle\Entity\Estimate $estimate
     *
     * @return Project
     */
    public function setEstimate(\Sinenco\ProjectBundle\Entity\Estimate $estimate = null)
    {
        $this->estimate = $estimate;

        return $this;
    }

    /**
     * Get estimate
     *
     * @return \Sinenco\ProjectBundle\Entity\Estimate
     */
    public function getEstimate()
    {
        return $this->estimate;
    }
}
