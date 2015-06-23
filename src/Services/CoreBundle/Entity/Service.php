<?php

namespace Services\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shop\ProductBundle\Entity\Product,
    Services\CoreBundle\Entity\Renting,
    Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Service
 *
 * @ORM\Table("services")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Service {

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Shop\ProductBundle\Entity\Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;
    
    /**
     * @ORM\ManyToMany(targetEntity="Services\CoreBundle\Entity\DetailName", cascade={"persist"})
     */
    private $detailsName;

    /**
     * @ORM\OneToMany(targetEntity="Services\CoreBundle\Entity\Renting", mappedBy="service", cascade={"all"}, orphanRemoval=true)
     */
    private $rentings;

    /**
     * @ORM\Column(name="ioncube", type="boolean", nullable=true)
     */
    private $useIoncube ;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->rentings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Service
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
     * Add rentings
     *
     * @param \Services\CoreBundle\Entity\Renting $rentings
     * @return Service
     */
    public function addRenting(Renting $rentings) {
        $this->rentings[] = $rentings;

        return $this;
    }

    /**
     * Remove rentings
     *
     * @param \Services\CoreBundle\Entity\Renting $rentings
     */
    public function removeRenting(Renting $rentings) {
        $this->rentings->removeElement($rentings);
    }

    /**
     * Get rentings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRentings() {
        return $this->rentings;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return Service
     */
    public function setProduct(Product $product = null) {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product 
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Set detailsName
     *
     * @param array $detailsName
     * @return Service
     */
    public function setDetailsName($detailsName) {
        $this->detailsName = $detailsName;

        return $this;
    }

    /**
     * Get detailsName
     *
     * @return array 
     */
    public function getDetailsName() {
        return $this->detailsName;
    }


    /**
     * Add detailsName
     *
     * @param \Services\CoreBundle\Entity\DetailName $detailsName
     * @return Service
     */
    public function addDetailsName(\Services\CoreBundle\Entity\DetailName $detailsName)
    {
        $this->detailsName[] = $detailsName;

        return $this;
    }

    /**
     * Remove detailsName
     *
     * @param \Services\CoreBundle\Entity\DetailName $detailsName
     */
    public function removeDetailsName(\Services\CoreBundle\Entity\DetailName $detailsName)
    {
        $this->detailsName->removeElement($detailsName);
    }
    
    
    public function __toString() {
        return $this->name;
    }

    /**
     * Set category
     *
     * @param \Shop\ProductBundle\Entity\Category $category
     * @return Service
     */
    public function setCategory(\Shop\ProductBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Shop\ProductBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set useIoncube
     *
     * @param boolean $useIoncube
     *
     * @return Service
     */
    public function setUseIoncube($useIoncube)
    {
        $this->useIoncube = $useIoncube;

        return $this;
    }

    /**
     * Get useIoncube
     *
     * @return boolean
     */
    public function getUseIoncube()
    {
        return $this->useIoncube;
    }
}
