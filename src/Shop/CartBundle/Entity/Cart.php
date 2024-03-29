<?php

namespace Shop\CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shop\CartBundle\Entity\CartItem;
use Shop\ProductBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Sinenco\UserBundle\Entity\User;

/**
 * Cart
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\CartBundle\Entity\CartRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Cart {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $complete;

    /**
     *  @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", cascade={"persist"})
     *  @ORM\JoinTable()
     *  @ORM\JoinColumn(nullable=null)
     * */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="lastUpdate", type="datetime")
     */
    private $lastUpdate;

    /**
     * @ORM\OneToMany(
     *    targetEntity="Shop\CartBundle\Entity\CartItem", 
     *    mappedBy="cart", 
     *    cascade={"persist", "remove", "merge"})
     * @ORM\JoinColumn(nullable=true,referencedColumnName="cart_id",onDelete="CASCADE") 
     */
    private $products;

    /**
     *  @ORM\ManyToOne(targetEntity="Shop\UserBundle\Entity\UserAddress", cascade={"persist"})
     *  @ORM\JoinTable()
     * */
    private $billingAddress;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    public function __toString() {
        return "$this->id";
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateLastTime() {
        if ($this->comment == null) {
            $this->comment = "";
        }
        $this->lastUpdate = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist() {
        $this->complete = false;
        $this->createdAt = new \DateTime();
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->products = new ArrayCollection();
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
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Cart
     */
    public function setLastUpdate($lastUpdate) {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime 
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * Add products
     *
     * @param \Shop\CartBundle\Entity\CartItem $products
     * @return Cart
     */
    public function addProduct(CartItem $products) {
        $products->setCart($this);
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Shop\CartBundle\Entity\CartItem $products
     */
    public function removeProduct(CartItem $products) {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts() {
        return $this->products;
    }

    /**
     * Set billingAddress
     *
     * @param \Shop\UserBundle\Entity\UserAddress $billingAddress
     * @return CartItem
     */
    public function setBillingAddress(\Shop\UserBundle\Entity\UserAddress $billingAddress = null) {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return \Shop\UserBundle\Entity\UserAddress 
     */
    public function getBillingAddress() {
        return $this->billingAddress;
    }

    /**
     * Set user
     *
     * @param \Shop\UserBundle\Entity\User $user
     * @return Cart
     */
    public function setUser(User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Shop\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Cart
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

    /**
     * Set complete
     *
     * @param boolean $complete
     *
     * @return Cart
     */
    public function setComplete($complete) {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete
     *
     * @return boolean
     */
    public function getComplete() {
        return $this->complete;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cart
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

}
