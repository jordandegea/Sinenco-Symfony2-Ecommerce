<?php

namespace Sinenco\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller {

    public function listAction() {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('SinencoBlogBundle:Post')->findBy(
                array(
            'enabled' => true
                ), array(
            "id" => "DESC"
                ), 10, null);



        return $this->render('SinencoBlogBundle:Blog:list.html.twig', array('posts' => $posts));
    }

}
