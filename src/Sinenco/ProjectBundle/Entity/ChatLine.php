<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Sinenco\ProjectBundle\Entity\Project;

/**
 * ChatLine
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ChatLine {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ProjectBundle\Entity\Project", inversedBy="chatLines", cascade={"remove"})
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="isClient", type="boolean")
     */
    private $isClient;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    public function __toString() {
        return (string)$this->id;
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
     * Set isClient
     *
     * @param boolean $isClient
     *
     * @return ChatLine
     */
    public function setIsClient($isClient) {
        $this->isClient = $isClient;

        return $this;
    }

    /**
     * Get isClient
     *
     * @return boolean
     */
    public function getIsClient() {
        return $this->isClient;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ChatLine
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set project
     *
     * @param \Sinenco\ProjectBundle\Entity\Project $project
     *
     * @return ChatLine
     */
    public function setProject($project = null) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Sinenco\ProjectBundle\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ChatLine
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

}
