<?php

namespace Shop\PaymentBundle\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Sinenco\AllopassPaymentBundle\Events\AllopassPaymentCallbackEvent;
use Shop\PaymentBundle\Entity\Invoice;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class AllopassCallbackListener {

    private $em;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    public function onCallbackAllopass(AllopassPaymentCallbackEvent $event) {
        $repository = $this->em->getRepository("ShopPaymentBundle:Invoice");
        $transaction = $event->getTransaction();

        if ($event->isFirstTime()) {
            $invoice = $repository->find($transaction->getData());

            if ($invoice != null) {
                $user = $invoice->getUser();
                // et on ajoute du credit à la facture
                $payout_amount = $transaction->getPayoutAmount();
		$payout_currency = $transaction->getPayoutCurrency();
                
		if ( $payout_amount == 0 ){
			//alors c'est un code de test
			$payout_amount = 1 ; 
			$payout_currency = EUR ; 
		}

                $user_currency = $user->getCurrency();

                $user_credit_plus = $this->container->get('shop_core.currency')->convertPrice(
                        $payout_amount, $payout_currency, $user_currency
                );
                
                $user->setBalance($user->getBalance() + $user_credit_plus);
                $this->em->persist($user);
		$this->em->flush();
            }
        }
    }

}
