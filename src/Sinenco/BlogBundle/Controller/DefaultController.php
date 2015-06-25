<?php

namespace Sinenco\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SinencoBlogBundle:Default:index.html.twig', array('name' => $name));
    }
}
