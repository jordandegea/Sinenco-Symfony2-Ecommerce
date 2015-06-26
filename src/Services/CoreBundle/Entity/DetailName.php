<?php

namespace Services\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * DetailName
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DetailName {

    use ORMBehaviors\Translatable\Translatable;
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
     * @ORM\Column( type="boolean")
     * @ORM\JoinColumn(nullable=true)
     */
    private $isEditableForCreation;

    /**
     * @ORM\Column( type="boolean")
     * @ORM\JoinColumn(nullable=true)
     */
    private $isDisplayedOnList;

    /**
     * @ORM\OneToOne(targetEntity="Shop\ProductBundle\Entity\ProductOption", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $attribute;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     * @return DetailName
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
     * Set isEditableForCreation
     *
     * @param boolean $isEditableForCreation
     * @return DetailName
     */
    public function setIsEditableForCreation($isEditableForCreation) {
        $this->isEditableForCreation = $isEditableForCreation;

        return $this;
    }

    /**
     * Get isEditableForCreation
     *
     * @return boolean 
     */
    public function getIsEditableForCreation() {
        return $this->isEditableForCreation;
    }

    /**
     * Set isDisplayedOnList
     *
     * @param boolean $isDisplayedOnList
     * @return DetailName
     */
    public function setIsDisplayedOnList($isDisplayedOnList) {
        $this->isDisplayedOnList = $isDisplayedOnList;

        return $this;
    }

    /**
     * Get isDisplayedOnList
     *
     * @return boolean 
     */
    public function getIsDisplayedOnList() {
        return $this->isDisplayedOnList;
    }

    /**
     * Set isEditable
     *
     * @param boolean $isEditable
     * @return DetailName
     */
    public function setIsEditable($isEditable) {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * Get isEditable
     *
     * @return boolean 
     */
    public function getIsEditable() {
        return $this->isEditable;
    }

    /**
     * Set attribute
     *
     * @param \Shop\ProductBundle\Entity\ProductOption $attribute
     * @return DetailName
     */
    public function setAttribute(\Shop\ProductBundle\Entity\ProductOption $attribute = null) {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute
     *
     * @return \Shop\ProductBundle\Entity\ProductOption
     */
    public function getAttribute() {
        return $this->attribute;
    }

}
