<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Task {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ProjectBundle\Entity\Task", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="smallint")
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity="Sinenco\ProjectBundle\Entity\Task", mappedBy="parent", cascade="persist")
     */
    private $tasks;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function __toString() {
        return (string) $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Task
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->value = 0;
        $this->description = "";
        $this->name = "";
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parent
     *
     * @param \Sinenco\ProjectBundle\Entity\Task $parent
     *
     * @return Task
     */
    public function setParent(\Sinenco\ProjectBundle\Entity\Task $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Sinenco\ProjectBundle\Entity\Task
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add task
     *
     * @param \Sinenco\ProjectBundle\Entity\Task $task
     *
     * @return Task
     */
    public function addTask(\Sinenco\ProjectBundle\Entity\Task $task) {
        $this->tasks[] = $task;
        $task->setParent($this);

        return $this;
    }

    /**
     * Remove task
     *
     * @param \Sinenco\ProjectBundle\Entity\Task $task
     */
    public function removeTask(\Sinenco\ProjectBundle\Entity\Task $task) {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks() {
        return $this->tasks;
    }

}
