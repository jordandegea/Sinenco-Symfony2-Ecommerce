<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\ProductBundle\Entity\CategoryRepository")
 */
class Category {

    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=true,onDelete="cascade") 
     */
    private $parentCategory;

    /**
     * @ORM\Column(name="canonical_name", type="string", length=255)
     * @Assert\Regex("/^[a-z0-9\-]+$/")
     * @Assert\Length(min=2)
     */
    private $canonicalName;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set parentCategory
     *
     * @param \Shop\ProductBundle\Entity\Category $parentCategory
     * @return Category
     */
    public function setParentCategory(Category $parentCategory = null) {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \Shop\ProductBundle\Entity\Category 
     */
    public function getParentCategory() {
        return $this->parentCategory;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     * @return Category
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

    /* Magics methods */

    public function __construct() {
        $this->names = new ArrayCollection();
    }

    public function __toString() {
        return $this->canonicalName;
    }

    public function preUpdate($object) {
        foreach ($object->getNames() as $name) {
            $name->setCategory($object);
        }
    }

}
