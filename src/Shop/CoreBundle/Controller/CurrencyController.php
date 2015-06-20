<?php

namespace Shop\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller {

    public function editCurrencyAction($currency) {
        $response = new Response();
        
        if ( $this->get("shop_core.currency")->setCurrency($currency, $response) ) {
            $response->setContent("1") ; // OK
        }else{
            $response->setContent("0") ; // K0
        }
        return $response ; 
    }

}
