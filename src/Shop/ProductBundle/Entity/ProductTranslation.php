<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class ProductTranslation {

    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $short_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $long_description;

    /**
     * @return string
     */
    public function getShortDescription() {
        return $this->short_description;
    }

    /**
     * @param  string
     * @return null
     */
    public function setShortDescription($description) {
        $this->short_description = $description;
    }

    /**
     * Set long_description
     *
     * @param string $longDescription
     * @return ProductTranslation
     */
    public function setLongDescription($longDescription) {
        $this->long_description = $longDescription;

        return $this;
    }

    /**
     * Get long_description
     *
     * @return string 
     */
    public function getLongDescription() {
        return $this->long_description;
    }

    /**
     * Set names
     *
     * @param string $name
     * @return ProductTranslation
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get names
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

}
