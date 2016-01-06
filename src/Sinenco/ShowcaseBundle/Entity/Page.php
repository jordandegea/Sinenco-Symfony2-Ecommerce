<?php

namespace Sinenco\ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Page {

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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     *
     * @ORM\OneToMany(targetEntity="Sinenco\ShowcaseBundle\Entity\LanguagePage", mappedBy="page", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $languagePages;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     *
     * @return Page
     */
    public function setCanonicalName($canonicalName) {
        $this->canonicalName = $canonicalName;

        return $this;
    }

    /**
     * Get canonicalName
     *
     * @return string
     */
    public function getCanonicalName() {
        return $this->canonicalName;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->languagePages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setLanguagePages($languagePages) {
        if (count($languagePages) > 0) {
            foreach ($languagePages as $i) {
                $this->addLanguagePage($i);
            }
        }

        return $this;
    }

    /**
     * Add languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage
     *
     * @return Page
     */
    public function addLanguagePage(\Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage) {
        $this->languagePages[] = $languagePage;
        $languagePage->setPage($this);
        return $this;
    }

    /**
     * Remove languagePage
     *
     * @param \Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage
     */
    public function removeLanguagePage(\Sinenco\ShowcaseBundle\Entity\LanguagePage $languagePage) {

        $this->languagePages->removeElement($languagePage);
    }

    /**
     * Get languagePages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguagePages() {
        return $this->languagePages;
    }

    public function __toString() {
        return $this->canonicalName;
    }


    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Page
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
