<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sinenco\ProjectBundle\Entity\Document,
    Sinenco\ProjectBundle\Entity\Estimate;
use Sinenco\UserBundle\Entity\User,
    Sinenco\ProjectBundle\Entity\ChatLine,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ProjectFile {

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * 
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media",cascade={"persist", "remove"})
     */
    private $file;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProjectFile
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
     * Set file
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $file
     *
     * @return ProjectFile
     */
    public function setFile(\Application\Sonata\MediaBundle\Entity\Media $file = null) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getFile() {
        return $this->file;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
