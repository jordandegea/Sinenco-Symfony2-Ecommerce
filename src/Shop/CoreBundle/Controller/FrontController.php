<?php

namespace Shop\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller {

    public function indexAction() {
        
        return $this->render('ShopCoreBundle:Front:index.html.twig');
    }

}
