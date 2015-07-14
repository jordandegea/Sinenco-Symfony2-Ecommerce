<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Shop\ProductBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\EventDispatcher\EventDispatcher ;

/**
 * Purchases
 *
 * @ORM\Table(name="purchases")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Purchases {

    const STATE_COMPLETE = 0;
    const STATE_PENDING = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint")
     */
    private $state;

    /**
     * @var array
     *
     * @ORM\Column(name="optionsValues", type="array")
     */
    private $optionsValues;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=true) 
     */
    private $product;

    /**
     * @ORM\Column(type="datetime")
     */
    private $purchasedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    
    public function __toString() {
        return (string) $this->id ;
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
     * Set state
     *
     * @param integer $state
     *
     * @return Purchases
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set optionsValues
     *
     * @param array $optionsValues
     *
     * @return Purchases
     */
    public function setOptionsValues($optionsValues) {
        $this->optionsValues = $optionsValues;

        return $this;
    }

    /**
     * Get optionsValues
     *
     * @return array
     */
    public function getOptionsValues() {
        return $this->optionsValues;
    }

    /**
     * Set purchasedAt
     *
     * @param \DateTime $purchasedAt
     *
     * @return Purchases
     */
    public function setPurchasedAt($purchasedAt) {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    /**
     * Get purchasedAt
     *
     * @return \DateTime
     */
    public function getPurchasedAt() {
        return $this->purchasedAt;
    }

    /**
     * Set file
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $file
     *
     * @return Purchases
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
     * Set product
     *
     * @param \Shop\ProductBundle\Entity\Product $product
     *
     * @return Purchases
     */
    public function setProduct(\Shop\ProductBundle\Entity\Product $product = null) {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Shop\ProductBundle\Entity\Product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     *
     * @return Purchases
     */
    public function setUser(\Sinenco\UserBundle\Entity\User $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Purchases
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

}
