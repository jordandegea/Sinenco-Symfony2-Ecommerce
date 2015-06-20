<?php

namespace Services\CoreBundle\Services;

use Doctrine\ORM\EntityManager,
    Symfony\Component\DependencyInjection\Container,
 //Sonata\AdminBundle\Event\PersistenceEvent,
    Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Services\CoreBundle\Entity\Detail,
    Services\CoreBundle\Entity\DetailName;


class CoreServices {

    protected $container;
    protected $em;
    protected $response;
    protected $request;

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $response = $event->getResponse();
        $request = $event->getRequest();
        $this->response = $response;
        $this->request = $request;
    }

    public function updateDetailsOfRentings(/* PersistenceEvent $args */) {
        //On cherche le nom du service dans les arguments 
        $required = array('name', 'translations', 'category', 'product');

        foreach ($this->request->request->getIterator() as $line) {
            if (is_array($line) && count(array_intersect_key(array_flip($required), $line)) === count($required)) {
                //All required keys exist!     
                $name = $line["name"];
                break;
            }
        }

        //Si on a trouvé
        if (isset($name)) {
            // On recupere le service
            $service = $this
                    ->em
                    ->getRepository('ServicesCoreBundle:Service')
                    ->findOneByName($name);
            // On recupere les locations
            $rentings = $this
                    ->em
                    ->getRepository('ServicesCoreBundle:Renting')
                    ->findByService($service);

            //Pour chaque location, on met à jour les details.
            $this->addDetailsIfNotExist($rentings, $service);
            
            $product = $service->getProduct();
            if ( $product != null ){
                $this->updateAttributesOfProduct($service, $product);
            }
        }
        return true;
    }

    private function updateAttributesOfProduct($service, $product){
        /* Product n'est pas null, on a vérifier avant 
         * Service non plus */
        
        $productOptions = $product->getOptions() ; 
        $serviceDetailNames = $service->getDetailsName() ;
        
        foreach ( $serviceDetailNames as $detailName){
            $present = false ;
            foreach ( $productOptions as $attribute ){
                if ( $detailName->getAttribute() == $attribute ){
                    $present = true ; 
                }
            }
            if ( !$present ){
                $product->addAttribute($detailName->getAttribute());
            }
        }
        
        $this->em->persist($product);
        $this->em->flush();
    }
    
    private function addDetailsIfNotExist($rentings, $service) {

        foreach ($rentings as $renting) {
            $added = false;
            foreach ($service->getDetailsName() as $detailOnService) {
                if (!$this->hasDetail($detailOnService, $renting)) {
                    // Alors on l'ajoute
                    $added = true;
                    $detail = new Detail();
                    $detail->setDetailName($detailOnService);
                    $renting->addDetail($detail);
                }
            }
            $this->em->persist($renting);
        }
        $this->em->flush();
    }

    private function hasDetail($detailOnService, $renting) {
        foreach ($renting->getDetails() as $detailOnRenting) {
            if ($detailOnRenting->getDetailName() == $detailOnService) {
                return true;
            }
        }
        return false;
    }

}
