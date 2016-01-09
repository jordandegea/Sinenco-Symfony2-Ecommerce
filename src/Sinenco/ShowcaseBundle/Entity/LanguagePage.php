<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LanguagePage
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $imageShowcase;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $imageIntro;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $imageBanner;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="color_text_intro", type="string", length=6)
     */
    private $colorTextIntro;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color_text_banner", type="string", length=6)
     */
    private $colorTextBanner;
    
    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=5)
     */
    private $language;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255)
     */
    private $subtitle;


    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ShowcaseBundle\Entity\Page", inversedBy="languagePages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $page;
    
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Sinenco\ShowcaseBundle\Entity\Tab", mappedBy="languagePage", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $tabs;
    
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
     * @return LanguagePage
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
    
    
    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * Add tab
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Tab $tab
     *
     * @return LanguagePage
     */
    public function addTab(\Sinenco\ShowcaseBundle\Entity\Tab $tab)
    {
        $this->tabs[] = $tab;

        return $this;
    }

    /**
     * Remove tab
     *
     * @param \Sinenco\ShowcaseBundle\Entity\Tab $tab
     */
    public function removeTab(\Sinenco\ShowcaseBundle\Entity\Tab $tab)
    {
        $this->tabs->removeElement($tab);
    }

    /**
     * Get tabs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return LanguagePage
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
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return LanguagePage
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set imageIntro
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $imageIntro
     *
     * @return LanguagePage
     */
    public function setImageIntro(\Application\Sonata\MediaBundle\Entity\Media $imageIntro = null)
    {
        $this->imageIntro = $imageIntro;

        return $this;
    }

    /**
     * Get imageIntro
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImageIntro()
    {
        return $this->imageIntro;
    }

    /**
     * Set imageBanner
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $imageBanner
     *
     * @return LanguagePage
     */
    public function setImageBanner(\Application\Sonata\MediaBundle\Entity\Media $imageBanner = null)
    {
        $this->imageBanner = $imageBanner;

        return $this;
    }

    /**
     * Get imageBanner
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImageBanner()
    {
        return $this->imageBanner;
    }

    /**
     * Set colorTextIntro
     *
     * @param \color $colorTextIntro
     *
     * @return LanguagePage
     */
    public function setColorTextIntro($colorTextIntro)
    {
        $this->colorTextIntro = $colorTextIntro;

        return $this;
    }

    /**
     * Get colorTextIntro
     *
     * @return \color
     */
    public function getColorTextIntro()
    {
        return $this->colorTextIntro;
    }

    /**
     * Set colorTextBanner
     *
     * @param string $colorTextBanner
     *
     * @return LanguagePage
     */
    public function setColorTextBanner($colorTextBanner)
    {
        $this->colorTextBanner = $colorTextBanner;

        return $this;
    }

    /**
     * Get colorTextBanner
     *
     * @return string
     */
    public function getColorTextBanner()
    {
        return $this->colorTextBanner;
    }

    /**
     * Set imageShowcase
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $imageShowcase
     *
     * @return LanguagePage
     */
    public function setImageShowcase(\Application\Sonata\MediaBundle\Entity\Media $imageShowcase = null)
    {
        $this->imageShowcase = $imageShowcase;

        return $this;
    }

    /**
     * Get imageShowcase
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImageShowcase()
    {
        return $this->imageShowcase;
    }
}
