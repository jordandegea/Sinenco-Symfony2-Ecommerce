<?php

namespace Shop\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceLine
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class InvoiceLine {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\PaymentBundle\Entity\Invoice", inversedBy="lines", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity="Shop\PaymentBundle\Entity\InvoiceLineOption", mappedBy="invoiceLine", cascade={"persist"})
     */
    private $options; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_price", type="decimal", precision=10, scale=2)
     */
    private $unitPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint")
     */
    private $quantity;
    
    public function __toString() {
        return $this->id;
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
     *
     * @return InvoiceLine
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
     * Set unitPrice
     *
     * @param string $unitPrice
     *
     * @return InvoiceLine
     */
    public function setUnitPrice($unitPrice) {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return string
     */
    public function getUnitPrice() {
        return $this->unitPrice;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return InvoiceLine
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set invoice
     *
     * @param \Shop\PaymentBundle\Entity\Invoice $invoice
     *
     * @return InvoiceLine
     */
    public function setInvoice(\Shop\PaymentBundle\Entity\Invoice $invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \Shop\PaymentBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Add option
     *
     * @param \Shop\PaymentBundle\Entity\InvoiceLineOption $option
     *
     * @return InvoiceLine
     */
    public function addOption(\Shop\PaymentBundle\Entity\InvoiceLineOption $option)
    {
        $this->options[] = $option;
        $option->setInvoiceLine($this);
        return $this;
    }

    /**
     * Remove option
     *
     * @param \Shop\PaymentBundle\Entity\InvoiceLineOption $option
     */
    public function removeOption(\Shop\PaymentBundle\Entity\InvoiceLineOption $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }
}
