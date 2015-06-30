<?php

// src/Shop/CoreBundle/Services/Currency.php

namespace Shop\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Cookie,
    Symfony\Component\HttpKernel\Event\FilterResponseEvent,
    Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\DependencyInjection\Container,
    Doctrine\ORM\EntityManager;

class Currency {

    const CURRENCY_CHANGE_COMMISSION = 0.99;
    const DEFAULT_CURRENCY = "USD";

    private $currency;
    private $user;
    private $currencies;
    protected $container;
    protected $em;
    private $response;
    private $request;

    public function onControllerRequest(\Symfony\Component\HttpKernel\Event\FilterControllerEvent $event) {
        $this->request = $event->getRequest();
        $userToken = $this->container->get('security.context')->getToken();
        if ($userToken != null) {
            $userId = $userToken
                    ->getUser()
                    ->getId();
            $this->user = $this
                    ->em
                    ->getRepository('SinencoUserBundle:User')
                    ->find($userId);
            if ($this->user != null) {
                $this->setCurrency($this->user->getCurrency());
                return;
            }
        }
        $this->currency = $this->request->cookies->get('currency');

        if ($this->currency == null) {
            $this->setDefaultCurrency();
        }
    }

    public function setDefaultCurrency() {

        $this->setCurrency(self::DEFAULT_CURRENCY);
    }

    public function convertPrice($price, $from, $to = null) {
        if (!$this->isCurrencyExist($from)) {
            return $price;
        }
        if ($to != null) {
            if (!$this->isCurrencyExist($to)) {
                return $price;
            }
        } else {
            $to = $this->getCurrency();
        }
        return round($price * $this->currencies[$to]->getRate() / $this->currencies[$from]->getRate(), 2, PHP_ROUND_HALF_EVEN);
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getChoiceCurrency() {
        $choiceCurrency = array();
        foreach ($this->currencies as $key => $currency) {
            $choiceCurrency[$key] = $key;
        }
        return $choiceCurrency;
    }

    public function getCurrencyObject() {
        return $this
                        ->em
                        ->getRepository('ShopCoreBundle:Currencies')
                        ->findOneByCode($this->getCurrency());
    }

    public function getAssociatedCurrency($currency_name) {
        if ($this->container == null) {
            return null;
        }

        return $this
                        ->em
                        ->getRepository('ShopCoreBundle:Currencies')
                        ->findOneByCode($this->getCurrency());
    }

    public function setResponse($response) {
        $this->response = $response;
    }

    public function changeUserBalance($oldCurrency, $newCurrency, $user) {
        if ($oldCurrency != $user->getCurrency()) {
            $user->setBalance(
                    $this->convertPrice(
                            $user->getBalance(), $oldCurrency, $newCurrency
                    ) * self::CURRENCY_CHANGE_COMMISSION
            );
        }
    }

    public function setCurrency($currency, $response = null) {
        if ($response != null) {
            $this->setResponse($response);
        }
        if (!$this->isCurrencyExist($currency)) {
            return false;
        }
        if ($this->user != null) {
            if ( !empty($this->currency) && $this->currency != $currency ){
                $this->changeUserBalance($this->currency, $currency, $this->user) ;
            }
            $this->user->setCurrency($currency);

            $this->em->persist($this->user);
            $this->em->flush();
        }
        
        $this->currency = $currency;
        $cookie = new Cookie(
                'currency', $currency, time() + 3600 * 24 * 7);

        if ($response != null) {
            $this->response->headers->setCookie($cookie);
        }
        return true;
    }

    public function isCurrencyExist($currency) {
        return array_key_exists($currency, $this->currencies);
    }

    public function __construct(EntityManager $entityManager, Container $container) {
        $this->em = $entityManager;
        $this->container = $container;

        $currencies = $this
                ->em
                ->getRepository('ShopCoreBundle:Currencies')
                ->findAll();



        foreach ($currencies as $currency) {
            $this->currencies[$currency->getCode()] = $currency;
        }
    }

}
