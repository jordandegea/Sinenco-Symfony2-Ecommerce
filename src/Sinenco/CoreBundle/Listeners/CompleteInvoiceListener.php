<?php

namespace Sinenco\CoreBundle\Listeners;

use Shop\PaymentBundle\Events\CompleteInvoiceEvent;
use Services\CoreBundle\Entity\Service;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Services\CoreBundle\Entity\Renting;
use Shop\CartBundle\Services\CartService;
use Shop\CartBundle\Entity\Interfaces\CartOptionsInterface;
use Services\CoreBundle\Entity\Detail;

class CompleteInvoiceListener {

    private $invoice;
    protected $container;
    protected $em;

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    public function onInvoiceComplete(CompleteInvoiceEvent $invoiceEvent) {
        $this->invoice = $invoiceEvent->getInvoice();

        // Pour chaque produit, on regarde si un service est associé
        $serviceRepository = $this
                ->em
                ->getRepository('SinencoUserBundle:User')
        ;

        $cart = $this->invoice->getCart();
        $user = $cart->getUser();

        if ($user->getSponsor() != NULL) {
            $totalPrice = $this->invoice->getTotalPrice();
            $amountSponsorPrice = $this->getAmountSponsor($totalPrice);
            $sponsorPrice = $this->container
                    ->get('shop_core.currency')
                    ->convertPrice(
                    $amountSponsorPrice, $this->invoice->getCurrency()->getCode(), $user->getSponsor()->getCurrency());
            $user->getSponsor()->setBalance($user->getSponsor()->getBalance() + $sponsorPrice);
        }

        $this->em->flush();
    }

    public function getAmountSponsor($price) {
        $euroPrice = $this->container
                    ->get('shop_core.currency')
                    ->convertPrice(
                    $price, $this->invoice->getCurrency()->getCode(), "EUR");
        $retPrice = 0 ;
        if ( $euroPrice <= 10 ){
            return round($price * 0.10, 2);
        }else{
            $retPrice += round(10 * 0.10, 2);
            $price -= 10 ; 
            if ( $euroPrice <= 50 ){
                $retPrice += round($price * 0.05, 2);
            }else{
                $retPrice += round(50 * 0.05, 2);
                $price -= 50 ;
                if ( $euroPrice <= 200 ){
                    $retPrice += round($price * 0.02, 2);
                }else{
                    $retPrice += round(200 * 0.02, 2);
                    $price -= 200 ;
                    if ( $euroPrice <= 1000 ){
                        $retPrice += round($price * 0.01, 2);
                    }else{
                        $retPrice += round(1000 * 0.01, 2);
                        $price -= 1000 ;
                        $retPrice += round($price * 0.005, 2);
                    }
                }
            }
        }
        return $retPrice ; 
    }

}
