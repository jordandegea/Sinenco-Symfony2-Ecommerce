<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Section
{
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
     * @ORM\Column(name="canonicalName", type="string", length=255)
     */
    private $canonicalName;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ShowcaseBundle\Entity\Tab", inversedBy="sections")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tab;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Section
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Section
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
     * Set canonicalName
     *
     * @param string $canonicalName
     *
     * @return Section
     */
    public function setCanonicalName($canonicalName)
    {
        $this->canonicalName = $canonicalName;

        return $this;
    }

    /**
     * Get canonicalName
     *
     * @return string
     */
    public function getCanonicalName()
    {
        return $this->canonicalName;
    }
    
    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * Set tab
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Tab $tab
     *
     * @return Section
     */
    public function setTab(\Sinenco\ShowcaseBundle\Entity\Tab $tab = null)
    {
        $this->tab = $tab;
        $tab->addSection($this, false);
        return $this;
    }

    /**
     * Get tab
     *
     * @return \Sinenco\ShowcaseBundle\Entity\Tab
     */
    public function getTab()
    {
        return $this->tab;
    }
}
