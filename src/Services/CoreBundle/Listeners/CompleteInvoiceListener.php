<?php

namespace Services\CoreBundle\Listeners;

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

    
    private function onInvoiceCompleteForPurchases($cartItem, $user, $service){
        
    }
    
    
    private function onInvoiceCompleteForRentings($cartItem, $user, $service){
        // Le service existe, on a donc des choses à faire
            if ($cartItem->getConfiguration()[CartOptionsInterface::FIRST_TIME]) {
                //Alors on doit crée le renting associé au service
                $renting = new Renting();
                $renting->setService($service);
                $renting->setUser($user);

                foreach ($service->getDetailsName() as $detailName) {
                    $detail = new Detail();
                    $detail->setDetailName($detailName);
                    $detail->setValue($cartItem->getOptionsValues()[$detailName->getAttribute()->getCanonicalName()]);
                    $renting->addDetail($detail);
                }
            } else {
                // Alors c'est une modification d'option ou une prolongation du renting
                $rentingRepository = $this
                        ->em
                        ->getRepository('ServicesCoreBundle:Renting')
                ;
                $hiddenValues = $cartItem->getHiddenValues();
                if ($hiddenValues == null || !is_numeric($hiddenValues["renting"])) {
                    return ;
                }
                $renting = $rentingRepository->find($hiddenValues["renting"]);


                foreach ($service->getDetailsName() as $detailName) {
                    $detailNotFound = true;
                    foreach ($renting->getDetails() as $detail) {
                        if ($detail->getDetailName() == $detailName) {
                            $detailNotFound = false;
                            $detail->setValue($cartItem->getOptionsValues()[$detailName->getAttribute()->getCanonicalName()]);
                            break;
                        }
                    }
                    if ($detailNotFound) {
                        $detail = new Detail();
                        $detail->setDetailName($detailName);
                        $detail->setValue($cartItem->getOptionsValues()[$detailName->getAttribute()->getCanonicalName()]);
                        $renting->addDetail($detail);
                    }
                }
            }

            /* Maintenant on ajoute la temps de location qu el client a acheté */
            $expiration = clone $renting->getExpiration();
            $cartItemPrices = $cartItem->getPrices();
            if ($cartItemPrices->getMonthly() > 0) {
                $expiration->add(new \DateInterval("P" . $cartItemPrices->getMonthly() . "M"));
            }
            if ($cartItemPrices->getQuarterly() > 0) {
                $expiration->add(new \DateInterval("P" . (3 * $cartItemPrices->getQuarterly()) . "M"));
            }
            if ($cartItemPrices->getSemiannually() > 0) {
                $expiration->add(new \DateInterval("P" . (6 * $cartItemPrices->getSemiannually()) . "M"));
            }
            if ($cartItemPrices->getAnnually() > 0) {
                $expiration->add(new \DateInterval("P" . $cartItemPrices->getAnnually() . "Y"));
            }
            $renting->setExpiration($expiration);

            $renting->setLicense($this->createLicense($renting));
            
            $this->em->persist($renting);
    }
    
    public function onInvoiceComplete(CompleteInvoiceEvent $invoiceEvent) {
        $this->invoice = $invoiceEvent->getInvoice();

        // Pour chaque produit, on regarde si un service est associé
        $serviceRepository = $this
                ->em
                ->getRepository('ServicesCoreBundle:Service')
        ;

        $cart = $this->invoice->getCart();
        $user = $cart->getUser();

        foreach ($cart->getProducts() as $cartItem) {
            $service = $serviceRepository->findOneByRentingProduct($cartItem->getProduct());
            if ($service != null) {
                $this->onInvoiceCompleteForRentings($cartItem, $user, $service);
            }else{
                $service = $serviceRepository->findOneByPurchaseProduct($cartItem->getProduct());
                $this->onInvoiceCompleteForPurchases($cartItem, $user, $service);
            }
        }
        $this->em->flush();
    }

    private function createLicense(Renting $renting) {

        foreach ($renting->getDetails() as $detail) {
            if ( strstr ( $detail->getDetailName() , "domain-name" ) ) {
                $server = $detail->getValue();
                break;
            }
        }
        
        if ( !isset($server) ){
            die();
        }
        
        $passphrase = $this->container->getParameter(
                'core_service.'
                .'services_available.'
                .$renting->getService()->getName().'.'
                .'passphrase');
        
        $expire = $renting->getExpiration()->format('Y-m-d');
        
        $cmd = "make_license --passphrase $passphrase " 
            . "--header-line '<?php exit(0); ?>' "
            . "--property \"Server = '$server'\" "
            . "--allowed-server $server "
            . "--expire-on $expire "
            . "--expose-expiry" ;
        
        $ret = shell_exec($cmd) ;
        
        // On peut faire un test sur ret
        
        return $ret ; 
        
    }

}
