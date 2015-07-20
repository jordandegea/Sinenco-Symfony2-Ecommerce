<?php

namespace Sinenco\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    public function indexAction()
    {
        return $this->render('SinencoProjectBundle::index.html.twig', array());
    }
}
