<?php

namespace Shop\ProductBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Shop\CoreBundle\Entity\Currencies,
    Shop\ProductBundle\Entity\Purchases,
    Shop\PaymentBundle\Events\CompleteInvoiceEvent;

class ProductService {

    protected $container;
    protected $response;
    protected $em;

    /* Return the currency choosen
     * @return string
     */

    public function onKernelRequest(GetResponseEvent $event) {
        $response = $event->getResponse();
        $request = $event->getRequest();
        $this->response = $response;
        $this->request = $request;
    }

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getCurrencyIfNotDefined($currency) {
        if ($currency == null) {
            return $this->getCurrency();
        }
        return $currency;
    }

    public function getCurrency() {
        return $this->container->get('shop_core.currency')->getCurrency();
    }

    public function getFormattedPrice(Currencies $currency, $price) {
        $newCurrency = $this->container->get('shop_core.currency')->getCurrencyObject();
        $price = $this->container->get('shop_core.currency')->convertPrice(
                $price, $currency->getCode(), $newCurrency->getCode());
        switch ($newCurrency->getFormat()) {
            case 1 : return $newCurrency->getPrefix() . " " . $price;
            case 2 : return $price . " " . $newCurrency->getPrefix();
            default : return $newCurrency->getPrefix() . " " . $price;
        }
    }

    public function onInvoiceComplete(CompleteInvoiceEvent $invoiceEvent) {

        $this->invoice = $invoiceEvent->getInvoice();

        $cart = $this->invoice->getCart();
        $user = $cart->getUser();

        foreach ($cart->getProducts() as $cartItem) {
            $purchase = new Purchases();
            $purchase->setFile($cartItem->getProduct()->getFile());
            $purchase->setProduct($cartItem->getProduct());
            $purchase->setOptionsValues(
                    $this->getFormattedOptionsValues(
                            $cartItem->getProduct()->getOptions(), $cartItem->getOptionsValues())
            );
            $purchase->setState(Purchases::STATE_PENDING);
            $purchase->setUser($user);
            $purchase->setPurchasedAt(new \DateTime);
            $cartItem->setPurchase($purchase);
            $this->em->persist($cartItem);
            $this->em->persist($purchase);
        }
        $this->em->flush();

        $this->container
                ->get("mail_service")
                ->sendMail($this->container, "Nouvel Achat", 
                        $this->container
                        ->getParameter("mailer_user"), 
                        "Nouvel Achat ( C'est peut être un service ) ");
    }

    private function getFormattedOptionsValues($options, $optionsValues) {
        $ret = [];
        foreach ($options as $option) {
            if (array_key_exists($option->getCanonicalName(), $optionsValues)) {
                $ret[$option->getCanonicalName()] = $optionsValues[$option->getCanonicalName()];
            }
        }
        return $ret;
    }

}
