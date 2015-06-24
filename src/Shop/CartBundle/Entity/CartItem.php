<?php

namespace Shop\CartBundle\Entity;

use Shop\CartBundle\Entity\Cart;
use Shop\ProductBundle\Entity\Product;
use Shop\UserBundle\Entity\UserAddress,
    Shop\CartBundle\Entity\Traits\CartOptionsTrait,
    Shop\CartBundle\Entity\Interfaces\CartOptionsInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\CartBundle\Entity\CartItemRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class CartItem implements CartOptionsInterface{

    use CartOptionsTrait ;
    
    public function _initCartOptionsTrait(){
        $this->__initCartOptionsTrait();
    }
    /** 
     * @ORM\PreUpdate 
     */  
    public function setUpdatedAt()  
    {  
        $this->__onPersistOrUpdate(); 
    }  
    /** 
     * @ORM\PrePersist 
     */  
    public function setCreatedAt()  
    {  
        $this->__onPersistOrUpdate();  
    }  
    
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\CartBundle\Entity\Cart", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $cart;

    /**
     * @ORM\OneToOne(targetEntity="Shop\CartBundle\Entity\CartItemPrices", 
     *    cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $prices;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    
    /**
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Purchases")
     * @ORM\JoinColumn(nullable=true)
     */
    private $purchase;
    
    /**
     * @var array
     *
     * @ORM\Column(name="optionsValues", type="array")
     */
    private $optionsValues;

    /**
     * @var array
     *
     * @ORM\Column(name="hiddenValues", type="array")
     */
    private $hiddenValues;
    
    
    public function __construct() {
        $this->hiddenValues = array() ;
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
     * Set cart
     *
     * @param string $cart
     * @return CartItem
     */
    public function setCart($cart) {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return string 
     */
    public function getCart() {
        return $this->cart;
    }

    /**
     * Set product
     *
     * @param \stdClass $product
     * @return Cart
     */
    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass 
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Add optionsValues
     *
     * @param array $optionsValues
     * @return Cart
     */
    public function addOptionsValues($field, $optionValue) {
        $this->optionsValues[$field] = $optionValue;

        return $this;
    }

    /**
     * Set optionsValues
     *
     * @param array $optionsValues
     * @return Cart
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
     * Set prices
     *
     * @param \Shop\CartBundle\Entity\CartItemPrices $prices
     * @return CartItem
     */
    public function setPrices(\Shop\CartBundle\Entity\CartItemPrices $prices) {
        $this->prices = $prices;

        return $this;
    }

    /**
     * Get prices
     *
     * @return \Shop\CartBundle\Entity\CartItemPrices 
     */
    public function getPrices() {
        return $this->prices;
    }

    
    public function addHiddenValues($field, $optionValue) {
        $this->hiddenValues[$field] = $optionValue;

        return $this;
    }
    /**
     * Set hiddenValues
     *
     * @param array $hiddenValues
     * @return CartItem
     */
    public function setHiddenValues($hiddenValues)
    {
        $this->hiddenValues = $hiddenValues;

        return $this;
    }

    /**
     * Get hiddenValues
     *
     * @return array 
     */
    public function getHiddenValues()
    {
        return $this->hiddenValues;
    }


    /**
     * Set purchase
     *
     * @param \Shop\ProductBundle\Entity\Purchases $purchase
     *
     * @return CartItem
     */
    public function setPurchase(\Shop\ProductBundle\Entity\Purchases $purchase = null)
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * Get purchase
     *
     * @return \Shop\ProductBundle\Entity\Purchases
     */
    public function getPurchase()
    {
        return $this->purchase;
    }
}
