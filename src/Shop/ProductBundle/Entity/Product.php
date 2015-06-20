<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Shop\ProductBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\MediaBundle\Entity\Media;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Shop\ProductBundle\Entity\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product {

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
     * @ORM\Column(name="canonical_name", type="string", length=255, unique=true)
     * @Assert\Regex("/^[a-z0-9\-]+$/")
     * @Assert\Length(min=2)
     */
    private $canonicalName;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $file;
    
    /**
     * @ORM\OneToOne(targetEntity="Shop\ProductBundle\Entity\Prices", cascade={"persist"})
     */
    private $price;


    /**
     * @ORM\ManyToMany(targetEntity="Shop\ProductBundle\Entity\ProductOption", 
     * cascade={"persist", "refresh", "detach"}) 
     * @ORM\JoinColumn(nullable=true)
     */
    private $options;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /*
      private $availableOn ;
      private $metaKeywords ;
      private $metaDescription ;
      private $variants ;
     */

    /**
     * Constructor
     */
    public function __construct() {
    }

    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onUpdate() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist() {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
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
     * Set category
     *
     * @param \Shop\ProductBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Shop\ProductBundle\Entity\Category 
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     * @return Product
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
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return Product
     */
    public function setImage(Media $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Product
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Product
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Add options
     *
     * @param \Shop\ProductBundle\Entity\ProductOption $options
     * @return Product
     */
    public function addOption(\Shop\ProductBundle\Entity\ProductOption $options) {
        $this->options[] = $options;

        return $this;
    }

    /**
     * Remove options
     *
     * @param \Shop\ProductBundle\Entity\ProductOption $options
     */
    public function removeOption(\Shop\ProductBundle\Entity\ProductOption $options) {
        $this->options->removeElement($options);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOptions() {
        return $this->options;
    }


    /**
     * Set price
     *
     * @param \Shop\ProductBundle\Entity\Prices $price
     *
     * @return Product
     */
    public function setPrice(\Shop\ProductBundle\Entity\Prices $price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \Shop\ProductBundle\Entity\Prices
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set file
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $file
     *
     * @return Product
     */
    public function setFile(\Application\Sonata\MediaBundle\Entity\Media $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getFile()
    {
        return $this->file;
    }
}
