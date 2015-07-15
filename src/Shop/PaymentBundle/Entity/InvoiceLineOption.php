<?php

namespace Shop\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceLine
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class InvoiceLineOption {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\PaymentBundle\Entity\InvoiceLine", inversedBy="options", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoiceLine;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_price", type="decimal")
     */
    private $unitPrice;


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
     * Set invoiceLine
     *
     * @param \Shop\PaymentBundle\Entity\InvoiceLine $invoiceLine
     *
     * @return InvoiceLineOption
     */
    public function setInvoiceLine(\Shop\PaymentBundle\Entity\InvoiceLine $invoiceLine)
    {
        $this->invoiceLine = $invoiceLine;

        return $this;
    }

    /**
     * Get invoiceLine
     *
     * @return \Shop\PaymentBundle\Entity\InvoiceLine
     */
    public function getInvoiceLine()
    {
        return $this->invoiceLine;
    }
}
