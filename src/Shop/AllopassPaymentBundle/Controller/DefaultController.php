<?php

namespace Shop\AllopassPaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ShopAllopassPaymentBundle:Default:index.html.twig', array('name' => $name));
    }
}
