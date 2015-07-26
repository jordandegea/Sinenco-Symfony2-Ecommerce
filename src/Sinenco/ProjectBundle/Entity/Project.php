<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sinenco\ProjectBundle\Entity\Document,
    Sinenco\ProjectBundle\Entity\Estimate;
use Sinenco\UserBundle\Entity\User,
    Sinenco\ProjectBundle\Entity\ChatLine,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sinenco\ProjectBundle\Entity\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Project {

    const STATE_WAITING_DEV = 0;
    const STATE_WAITING_USER = 1;
    const STATE_REFUSED = 2;
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
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

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
     * 
     * @ORM\ManyToMany(targetEntity="Sinenco\ProjectBundle\Entity\ProjectFile",cascade={"persist", "remove"})
     * @ORM\JoinTable(name="project_specifications",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id",unique=true)}
     *      )
     */
    private $specifications; // Cahier des charges

    /**
     * 
     * @ORM\ManyToMany(targetEntity="Sinenco\ProjectBundle\Entity\ProjectFile",cascade={"persist", "remove"})
     * @ORM\JoinTable(name="project_propositions",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id",unique=true)}
     *      )
     */
    private $propositions; // Cahier des charges

    
    /**
     * @ORM\OneToMany(targetEntity="Sinenco\ProjectBundle\Entity\ChatLine", mappedBy="project", cascade={"remove", "persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $chatLines;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="Sinenco\ProjectBundle\Entity\Task",cascade={"persist", "remove"})
     * @ORM\JoinTable(name="project_task",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id",unique=true)}
     *      )
     */
    private $tasks; 
    
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

    public function __toString() {
        return (string) $this->id;
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
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Project
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     *
     * @return Project
     */
    public function setUser(User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->chatLines = new ArrayCollection();
    }

    /**
     * Add chatLine
     *
     * @param \Sinenco\ProjectBundle\Entity\ChatLine $chatLine
     *
     * @return Project
     */
    public function addChatLine(ChatLine $chatLine) {
        $this->chatLines[] = $chatLine;
        $chatLine->setProject($this);
        return $this;
    }

    /**
     * Remove chatLine
     *
     * @param \Sinenco\ProjectBundle\Entity\ChatLine $chatLine
     */
    public function removeChatLine(ChatLine $chatLine) {
        $this->chatLines->removeElement($chatLine);
    }

    /**
     * Get chatLines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChatLines() {
        return $this->chatLines;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Project
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }


    /**
     * Add specification
     *
     * @param \Sinenco\ProjectBundle\Entity\ProjectFile $specification
     *
     * @return Project
     */
    public function addSpecification(\Sinenco\ProjectBundle\Entity\ProjectFile $specification)
    {
        $this->specifications[] = $specification;

        return $this;
    }

    /**
     * Remove specification
     *
     * @param \Sinenco\ProjectBundle\Entity\ProjectFile $specification
     */
    public function removeSpecification(\Sinenco\ProjectBundle\Entity\ProjectFile $specification)
    {
        $this->specifications->removeElement($specification);
    }

    /**
     * Get specifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    /**
     * Add proposition
     *
     * @param \Sinenco\ProjectBundle\Entity\ProjectFile $proposition
     *
     * @return Project
     */
    public function addProposition(\Sinenco\ProjectBundle\Entity\ProjectFile $proposition)
    {
        $this->propositions[] = $proposition;

        return $this;
    }

    /**
     * Remove proposition
     *
     * @param \Sinenco\ProjectBundle\Entity\ProjectFile $proposition
     */
    public function removeProposition(\Sinenco\ProjectBundle\Entity\ProjectFile $proposition)
    {
        $this->propositions->removeElement($proposition);
    }

    /**
     * Get propositions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropositions()
    {
        return $this->propositions;
    }

    /**
     * Add task
     *
     * @param \Sinenco\ProjectBundle\Entity\Task $task
     *
     * @return Project
     */
    public function addTask(\Sinenco\ProjectBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \Sinenco\ProjectBundle\Entity\Task $task
     */
    public function removeTask(\Sinenco\ProjectBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
