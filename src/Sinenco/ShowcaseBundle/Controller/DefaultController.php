<?php

namespace Sinenco\ShowcaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SinencoShowcaseBundle:Default:index.html.twig', array('name' => $name));
    }
}
