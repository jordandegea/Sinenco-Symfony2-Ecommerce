<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductOptionValues
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProductOptionValues {

    
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
     * @ORM\Column(name="canonical_name", type="string", length=255, unique=true)
     * @Assert\Regex("/^[a-z0-9\-]+$/")
     * @Assert\Length(min=2)
     */
    private $canonicalName;

    /**
     * @ORM\OneToOne(targetEntity="Shop\ProductBundle\Entity\Prices", cascade={"persist"})
     */
    private $price;

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
     * Constructor
     */
    public function __construct() {
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

}
