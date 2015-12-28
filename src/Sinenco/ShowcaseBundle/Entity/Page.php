<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Page
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
     *
     * @ORM\OneToMany(targetEntity="Sinenco\ShowcaseBundle\Entity\LanguagePage", mappedBy="page", cascade={"all"}, orphanRemoval=true)
     */
    private $languagePages;

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
     * Set canonicalName
     *
     * @param string $canonicalName
     *
     * @return Page
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->languagePages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\PageLanguage $languagePage
     *
     * @return Page
     */
    public function addLanguagePage(\Sinenco\ShowcaseBundle\Entity\PageLanguage $languagePage)
    {
        $this->languagePages[] = $languagePage;

        return $this;
    }

    /**
     * Remove languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\PageLanguage $languagePage
     */
    public function removeLanguagePage(\Sinenco\ShowcaseBundle\Entity\PageLanguage $languagePage)
    {
        $this->languagePages->removeElement($languagePage);
    }

    /**
     * Get languagePages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguagePages()
    {
        return $this->languagePages;
    }
}
