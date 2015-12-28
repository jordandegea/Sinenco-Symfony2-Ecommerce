<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageLanguage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class LanguagePage
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
     * @ORM\Column(name="canonicalName", type="string", length=255, unique=true)
     */
    private $canonicalName;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=5)
     */
    private $language;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ShowcaseBundle\Entity\Page", inversedBy="languagePages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $page;
    
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Sinenco\ShowcaseBundle\Entity\Section", mappedBy="languagePage", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $sections;
    
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
     * Set language
     *
     * @param string $language
     *
     * @return PageLanguage
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set page
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Page $page
     *
     * @return LanguagePage
     */
    public function setPage(\Sinenco\ShowcaseBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Sinenco\ShowcaseBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add section
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Section $section
     *
     * @return LanguagePage
     */
    public function addSection(\Sinenco\ShowcaseBundle\Entity\Section $section)
    {
        $this->sections[] = $section;
        $section->setLanguagePage($this);
        return $this;
    }

    /**
     * Remove section
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Section $section
     */
    public function removeSection(\Sinenco\ShowcaseBundle\Entity\Section $section)
    {
        $this->sections->removeElement($section);
    }

    /**
     * Get sections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return LanguagePage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     *
     * @return LanguagePage
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
}
