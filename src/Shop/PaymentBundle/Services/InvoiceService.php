<?php

namespace Shop\PaymentBundle\Services;

use Shop\PaymentBundle\Entity\Invoice;
use Shop\PaymentBundle\Entity\InvoiceAction;
use Shop\PaymentBundle\Entity\InvoiceObject;
use Shop\PaymentBundle\Entity\InvoiceTransaction;
use Shop\PaymentBundle\Entity\InvoiceObjectPrices;
use Shop\CartBundle\Entity\Cart;
use Shop\CartBundle\Entity\CartItem;
use Shop\CartBundle\Entity\CartItemPrices;
use Shop\ProductBundle\Entity\Prices;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Shop\PaymentBundle\Events\InvoiceCoreEvents;
use Shop\PaymentBundle\Events\CompleteInvoiceEvent;

class InvoiceService {

    const OK = 0;
    const INVOICE_INEXIST = 1;
    const TRANSACTION_EXIST = 2;
    const BALANCE_PAY_PARTIAL = 3;
    const BALANCE_PAY_COMPLETE = 4;
    const BALANCE_PAY_FAIL = 5;

    private $em;
    private $container;

    public function createInvoiceWithCart(Cart $cart) {

        $invoice = new Invoice();

        // On ajoute 0 à la facture car il n'y a aucune transaction pour le moment
        $invoice->setNumber(0);

        $invoice->setCredit(0.00);

        $invoice->setTotalPrice($this->container->get("shop_cart.cart")->getTotalPriceHT());

        $invoice->setAddressReceiver($cart->getBillingAddress()->__toString());

        $invoice->setAddressSender($this->container->get('twig')->getGlobals()['invoice']['sender_address']);

        $invoice->setCurrency($this->container->get('shop_core.currency')->getCurrencyObject());

        $invoice->setCart($cart);

        $invoice->setUser($cart->getUser());

        $invoice->setDate(new \DateTime);
        
        return $invoice;
    }

    public function addTransactionToInvoice($invoice_id, $txnId, $service, $value, $date = null) {

        $invoice = $this
                ->em
                ->getRepository('ShopPaymentBundle:Invoice')
                ->find($invoice_id);

        if ($invoice == null) {
            return self::INVOICE_INEXIST;
        }

        foreach ($invoice->getTransactions() as $transaction) {

            if ($transaction->getTxnId() == $txnId) {
                return self::TRANSACTION_EXIST;
            }
        }

        $transaction = new InvoiceTransaction();
        $transaction->setTxnId($txnId);
        $transaction->setService($service);
        $transaction->setValue($value);
        $transaction->setDate($date);

        $invoice->addTransaction($transaction);

        $this
                ->em
                ->flush();

        $this->checkIfInvoicePaid($invoice);
        return self::OK;
    }

    public function payWithBalance(Invoice $invoice) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $balance = $user->getBalance();

        if ($balance == 0.00) {
            return InvoiceService::BALANCE_PAY_FAIL;
        }
        $remainingPrice = $this->getRemainingPrice($invoice);

        if ($balance >= $remainingPrice) {
            $invoice->setCredit($invoice->getCredit() + $remainingPrice);

            $user->setBalance($user->getBalance() - $remainingPrice);
            //$this->em->flush();
            $this->checkIfInvoicePaid($invoice);
            return InvoiceService::BALANCE_PAY_COMPLETE;
        } else {
            $invoice->setCredit($balance);
            $user->setBalance(0.00);
            $this->em->flush();
            return InvoiceService::BALANCE_PAY_PARTIAL;
        }
    }

    private function checkIfInvoicePaid(Invoice $invoice) {

        if ($this->getRemainingPrice($invoice) <= 0) {

            $lastInvoicePaid = $this
                    ->em
                    ->getRepository('ShopPaymentBundle:Invoice')
                    ->findOneBy([], ['number' => 'DESC']);

            $invoice->setNumber($lastInvoicePaid->getNumber() + 1);
            $this->em->persist($invoice);
            $this->em->flush();

            $event = new CompleteInvoiceEvent($invoice);
            // On déclenche l'évènement
            $this
                    ->container
                    ->get('event_dispatcher')
                    ->dispatch(InvoiceCoreEvents::onInvoiceComplete, $event)
            ;
        }
    }

    private function getPriceArray(Prices $prices, CartItemPrices $quantities) {
        $returnArray = array();
        $this->addPriceToArray($prices, $quantities, $returnArray, "getMonthly");
        $this->addPriceToArray($prices, $quantities, $returnArray, "getQuarterly");
        $this->addPriceToArray($prices, $quantities, $returnArray, "getSemiannually");
        $this->addPriceToArray($prices, $quantities, $returnArray, "getAnnually");
        return $returnArray;
    }

    private function addPriceToArray(Prices $prices, CartItemPrices $quantities, &$array, $method) {
        if ($quantities->$method() > 0) {
            $array[] = $method . ";" . $prices->$method() . ";" . $quantities->$method();
        }
    }

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getSubTotalPriceHT(Invoice $invoice) {
        return $invoice->getTotalPrice();
    }

    public function getTax(Invoice $invoice) {
        return "0";
    }

    public function getSubTotalPriceTTC(Invoice $invoice) {
        return $this->getSubTotalPriceHT($invoice); // Pour le moment
    }

    public function getCredit(Invoice $invoice) {
        return $invoice->getCredit();
    }

    public function getTotalPriceTTC(Invoice $invoice) {
        return $this->getSubTotalPriceTTC($invoice) - $this->getCredit($invoice);
    }

    public function getRemainingPrice(Invoice $invoice) {

        $remainingPrice = $this->getTotalPriceTTC($invoice);

        foreach ($invoice->getTransactions() as $transaction) {
            $remainingPrice -= $transaction->getValue();
        }

        return $remainingPrice;
    }

    public function getTotalPriceCartItem($cartItem) {
        $price = $cartItem->getProduct()->getPrice();
        $totalPrice = $this->container->get('shop_cart.cart')->getTotalOfPrice($price, $cartItem);
        return $totalPrice;
    }

}
