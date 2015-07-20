<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chapter
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Chapter {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ProjectBundle\Entity\Document", inversedBy="parts", cascade={"remove"})
     * @ORM\JoinColumn(name="desk_id", referencedColumnName="id")
     */
    protected $document;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    /**
     * @var string
     *
     * @ORM\Column(name="content_temp", type="text")
     */
    private $content_temp;

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
     * @return Chapter
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
     * Set content
     *
     * @param string $content
     *
     * @return Chapter
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set contentTemp
     *
     * @param string $contentTemp
     *
     * @return Chapter
     */
    public function setContentTemp($contentTemp)
    {
        $this->content_temp = $contentTemp;

        return $this;
    }

    /**
     * Get contentTemp
     *
     * @return string
     */
    public function getContentTemp()
    {
        return $this->content_temp;
    }

    /**
     * Set document
     *
     * @param \Sinenco\ProjectBundle\Entity\Document $document
     *
     * @return Chapter
     */
    public function setDocument(\Sinenco\ProjectBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \Sinenco\ProjectBundle\Entity\Document
     */
    public function getDocument()
    {
        return $this->document;
    }
}
