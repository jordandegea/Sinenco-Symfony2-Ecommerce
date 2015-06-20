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

    const DEFAULT_CURRENCY = "USD";

    private $currencies ;
    protected $container;
    protected $em;
    private $response;
    private $request ;
    /**
     * Return the currency choosen
     *
     * @return string
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $this->request = $event->getRequest();

        $value = $this->request->cookies->get('currency');
        if ($value == null) {
            $this->setDefaultCurrency();
        }
    }

    public function setDefaultCurrency() {
        $cookie = new Cookie(
                'currency', self::DEFAULT_CURRENCY, time() + 3600 * 24 * 7);
        if ($this->response == null) {
            $this->response = new Response;
            $this->response->headers->setCookie($cookie);
            $this->response->send();
        } else {
            $this->response->headers->setCookie($cookie);
        }
    }

    public function convertPrice( $price, $from, $to = null ){
        if ( !$this->isCurrencyExist($from) ){
            return $price;
        }
        if ( $to != null ){
            if ( !$this->isCurrencyExist($to) ){
                return $price ;
            }
        }else{
            $to = $this->getCurrency();
        }
        return round($price * $this->currencies[$to]->getRate() / $this->currencies[$from]->getRate(), 2, PHP_ROUND_HALF_EVEN) ;
    }
    
    public function getCurrency() {
        if ($this->isCurrencyExist($this->container->get('request')->cookies->get('currency'))) {
            return $this->container->get('request')->cookies->get('currency');
        } else {
            $this->setDefaultCurrency();
            return self::DEFAULT_CURRENCY;
        }
    }

    
    public function getChoiceCurrency(){
        $choiceCurrency = array() ;
        foreach ( $this->currencies as $key => $currency ){
            $choiceCurrency[$key] = $key ;
        }
        return $choiceCurrency ; 
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
    
    public function setResponse($response){
        $this->response = $response ; 
    }
    
    public function setCurrency($currency, $response = null) {
        if ( $response != null ){
            $this->setResponse($response);
        }
        if (!$this->isCurrencyExist($currency)) {
            return false;
        }
        $cookie = new Cookie(
                'currency', $currency, time() + 3600 * 24 * 7);

        $this->response->headers->setCookie($cookie);
        return true ; 
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
