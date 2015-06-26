<?php

namespace Services\AllopassBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ServicesAllopassBundle:Default:index.html.twig', array('name' => $name));
    }
}
