<?php

namespace Shop\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Shop\PaymentBundle\Entity\InvoiceRepository")
 */
class Invoice {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;
    
    
    /**
     *  @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", cascade={"persist"})
     *  @ORM\JoinTable()
     *  @ORM\JoinColumn(nullable=null)
     * */
    private $user;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer" )
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="address_sender", type="string", length=512)
     */
    private $addressSender;

    /**
     * @var string
     *
     * @ORM\Column(name="address_receiver", type="string", length=512)
     */
    private $addressReceiver;

    /**
     * @ORM\OneToMany(targetEntity="Shop\PaymentBundle\Entity\InvoiceTransaction", mappedBy="invoice", cascade={"all"}, orphanRemoval=true)
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\CartBundle\Entity\Cart", inversedBy="products")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $cart ;
    
    /**
     * @ORM\ManyToOne(targetEntity="Shop\CoreBundle\Entity\Currencies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;
    
    /**
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $totalPrice;
    
    /**
     *
     * @ORM\Column(name="credit", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $credit;
    
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
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set number
     *
     * @param integer $number
     * @return Invoice
     */
    public function setNumber($number) {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Set addressSender
     *
     * @param string $addressSender
     * @return Invoice
     */
    public function setAddressSender($addressSender) {
        $this->addressSender = $addressSender;

        return $this;
    }

    /**
     * Get addressSender
     *
     * @return string 
     */
    public function getAddressSender() {
        return $this->addressSender;
    }

    /**
     * Set addressReceiver
     *
     * @param string $addressReceiver
     * @return Invoice
     */
    public function setAddressReceiver($addressReceiver) {
        $this->addressReceiver = $addressReceiver;

        return $this;
    }

    /**
     * Get addressReceiver
     *
     * @return string 
     */
    public function getAddressReceiver() {
        return $this->addressReceiver;
    }

    /**
     * Add transactions
     *
     * @param \Shop\PaymentBundle\Entity\InvoiceTransaction $transactions
     * @return Invoice
     */
    public function addTransaction(\Shop\PaymentBundle\Entity\InvoiceTransaction $transactions) {
        $this->transactions[] = $transactions;
        $transactions->setInvoice($this);
        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Shop\PaymentBundle\Entity\InvoiceTransaction $transactions
     */
    public function removeTransaction(\Shop\PaymentBundle\Entity\InvoiceTransaction $transactions) {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Invoice
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set currency
     *
     * @param \Shop\CoreBundle\Entity\Currencies $currency
     * @return Invoice
     */
    public function setCurrency(\Shop\CoreBundle\Entity\Currencies $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \Shop\CoreBundle\Entity\Currencies 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set credit
     *
     * @param string $credit
     * @return Invoice
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string 
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set cart
     *
     * @param \Shop\CartBundle\Entity\Cart $cart
     * @return Invoice
     */
    public function setCart(\Shop\CartBundle\Entity\Cart $cart)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \Shop\CartBundle\Entity\Cart 
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set totalPrice
     *
     * @param string $totalPrice
     * @return Invoice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return string 
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
}
