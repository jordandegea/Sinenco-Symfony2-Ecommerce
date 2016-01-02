<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tab
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tab
{
    
    const DEFAULT_COLOR = "default" ;
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
    private $image;
 
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="btn_color", type="string", length=16)
     */
    private $btnColor;
    

    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\ShowcaseBundle\Entity\LanguagePage", inversedBy="tabs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $languagePage;
    
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Sinenco\ShowcaseBundle\Entity\Section", mappedBy="tab", cascade={"all"}, orphanRemoval=true)
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
        $this->btnColor = self::DEFAULT_COLOR;
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
    public function addSection(\Sinenco\ShowcaseBundle\Entity\Section $section, $first = true)
    {
        $this->sections[] = $section;
        if ( $first ){
            $section->setTab($this);
        }
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
    
    
    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * Add image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     *
     * @return LanguagePage
     */
    public function addImage(\Application\Sonata\MediaBundle\Entity\Media $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     */
    public function removeImage(\Application\Sonata\MediaBundle\Entity\Media $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     *
     * @return LanguagePage
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage
     *
     * @return Tab
     */
    public function setLanguagePage(\Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage = null)
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
     * Set btnColor
     *
     * @param string $btnColor
     *
     * @return Tab
     */
    public function setBtnColor($btnColor)
    {
        $this->btnColor = $btnColor;

        return $this;
    }

    /**
     * Get btnColor
     *
     * @return string
     */
    public function getBtnColor()
    {
        return $this->btnColor;
    }
}
