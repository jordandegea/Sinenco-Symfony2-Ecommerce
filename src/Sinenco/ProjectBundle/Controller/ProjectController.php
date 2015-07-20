<?php

namespace Sinenco\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sinenco\ProjectBundle\Form\Type\NewProjectType;
use Sinenco\ProjectBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProjectController extends Controller {

    public function indexAction() {
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $projects = $repository->findByUser($this->getUser());


        return $this->render('SinencoProjectBundle::index.html.twig', array('projects' => $projects));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function detailAction($id) {
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $project = $repository->find($id);

        return $this->render('SinencoProjectBundle::detail.html.twig', array('projects' => $project));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {


        $project = new Project();

        $form = $this->get('form.factory')->create(new NewProjectType, $project);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $project->setUser($this->getUser());

            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('sinenco_project_homepage'));
        }

        return $this->render('SinencoProjectBundle::new.html.twig', array('form' => $form->createView()));
    }

}
