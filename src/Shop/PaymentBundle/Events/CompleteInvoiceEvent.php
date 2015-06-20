<?php
namespace Shop\PaymentBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Shop\PaymentBundle\Entity\Invoice;

class CompleteInvoiceEvent extends Event
{
  protected $invoice;

  public function __construct(Invoice $invoice)
  {
    $this->invoice = $invoice;
  }
  
  public function getInvoice(){
      return $this->invoice ;
  }

}