<?php

// src/Shop/CoreBundle/Services/Currency.php

namespace Sinenco\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Cookie,
    Symfony\Component\HttpKernel\Event\FilterResponseEvent,
    Symfony\Component\HttpKernel\Event\GetResponseEvent,
    Symfony\Component\DependencyInjection\Container,
    Doctrine\ORM\EntityManager, 
    Sinenco\UserBundle\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent ;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;

class SponsorService {

    protected $container;
    protected $em;
    private $request;
    private $sponsor = null ;
    private $sponsor_id;
    
    
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
 
        $this->request = $event->getRequest();
        
        if ($this->request->query->has("p")) {
            $this->sponsor_id = $this->request->query->get("p");
            if( is_numeric ($this->sponsor_id) ){
                $cookie = new Cookie('sponsor', $this->sponsor_id, time() + 3600 * 24 * 7, '/', null, false, false);

                $response->headers->setCookie($cookie);
                
                return ; 
            }
        }
        
        $this->storeSponsorId();
    }
    
    public function storeSponsorId(){
        if ($this->request == null ) {
            return ; 
        }
        $value = $this->request->cookies->get('sponsor');
        
        if ($value == null) {
            $this->sponsor_id = null;
        }else{
            $this->sponsor_id = $value ;
        }
    }
    
    public function __construct(EntityManager $entityManager, Container $container) {
        $this->em = $entityManager;
        $this->container = $container;
    }
    
    public function getSponsorId(){
        if ( getSponsor() != null ){
            return $this->sponsor_id ;
        }
        return null ; 
    }
    
    public function getSponsor(){
            if ( $this->sponsor == null && $this->sponsor_id != null ){
                $this->sponsor =  $this
                            ->em
                            ->getRepository('SinencoUserBundle:User')
                            ->find($this->sponsor_id);
            }
            return $this->sponsor ; 
    }

    public function onCompleteRegistration(FilterUserResponseEvent $event)
    {
        $this->request = $event->getRequest();
        
        $this->storeSponsorId();
        
        $user = $event->getUser();
        $user->setSponsor($this->getSponsor());
        $this->em->persist($user);
        $this->em->flush();
        
    }
}
