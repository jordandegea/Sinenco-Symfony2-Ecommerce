<?php

namespace Shop\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceTransaction
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class InvoiceTransaction {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shop\PaymentBundle\Entity\Invoice", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="txn_id", type="string", length=100)
     */
    private $txnId;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=255)
     */
    private $service;

    /**
     * @var decimal
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __toString() {
        return "$this->id";
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
     * Set txnId
     *
     * @param string $txnId
     * @return InvoiceTransaction
     */
    public function setTxnId($txnId) {
        $this->txnId = $txnId;

        return $this;
    }

    /**
     * Get txnId
     *
     * @return string
     */
    public function getTxnId() {
        return $this->txnId;
    }

    /**
     * Set service
     *
     * @param string $service
     * @return InvoiceTransaction
     */
    public function setService($service) {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string 
     */
    public function getService() {
        return $this->service;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return InvoiceTransaction
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return InvoiceTransaction
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set invoice
     *
     * @param \Shop\PaymentBundle\Entity\Invoice $invoice
     * @return InvoiceTransaction
     */
    public function setInvoice(\Shop\PaymentBundle\Entity\Invoice $invoice) {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \Shop\PaymentBundle\Entity\Invoice 
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist() {
        if ($this->date == null) {
            $this->date = new \DateTime();
        }
    }

}
