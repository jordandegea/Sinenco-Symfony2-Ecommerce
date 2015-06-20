<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ProductOption
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\ProductBundle\Entity\ProductOptionRepository")
 */
class ProductOption {

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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean")
     */
    private $required;

    /**
     * @ORM\Column( type="boolean")
     */
    private $isEditable;
    
    /**
     * @ORM\ManyToMany(targetEntity="Shop\ProductBundle\Entity\ProductOptionValues", cascade={"persist"})
     */
    private $values;

    public function __toString() {
        return $this->canonicalName;
    }

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
     * @return Attribute
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
     * Set type
     *
     * @param string $type
     * @return Attribute
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return Attribute
     */
    public function setRequired($required) {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean 
     */
    public function getRequired() {
        return $this->required;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add values
     *
     * @param \Shop\ProductBundle\Entity\ProductOptionValues $values
     * @return ProductOption
     */
    public function addValue(\Shop\ProductBundle\Entity\ProductOptionValues $values)
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Remove values
     *
     * @param \Shop\ProductBundle\Entity\ProductOptionValues $values
     */
    public function removeValue(\Shop\ProductBundle\Entity\ProductOptionValues $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Set isEditable
     *
     * @param boolean $isEditable
     * @return ProductOption
     */
    public function setIsEditable($isEditable)
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * Get isEditable
     *
     * @return boolean 
     */
    public function getIsEditable()
    {
        return $this->isEditable;
    }
}
