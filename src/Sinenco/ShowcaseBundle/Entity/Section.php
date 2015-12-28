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
     * @ORM\ManyToOne(targetEntity="Sinenco\ShowcaseBundle\Entity\LanguagePage", inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $languagePage;
    
    
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
     * Set languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage
     *
     * @return Section
     */
    public function setLanguagePage(\Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage)
    {
        $this->languagePage = $languagePage;

        return $this;
    }

    /**
     * Get languagePage
     *
     * @return \Sinenco\ShowcaseBundle\Entity\LanguagePage
     */
    public function getLanguagePage()
    {
        return $this->languagePage;
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
}
