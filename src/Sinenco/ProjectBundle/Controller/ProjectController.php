<?php

namespace Sinenco\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sinenco\ProjectBundle\Form\Type\NewProjectType,
    Sinenco\ProjectBundle\Form\Type\NewChatLineType;
use Sinenco\ProjectBundle\Entity\Project,
    Sinenco\ProjectBundle\Entity\ChatLine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProjectController extends Controller {

    public function indexAction() {
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $projects = $repository->findByUser($this->getUser());


        return $this->render('SinencoProjectBundle:Front:index.html.twig', array('projects' => $projects));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function detailAction(Request $request, $id) {
        
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $project = $repository->find($id);

        if ( $project->getUser() != $this->getUser() ){
            if ( ! $this->get('security.context')->isGranted('ROLE_ADMIN') ){
                return $this->redirect($this->generateUrl('sinenco_project_homepage'));
            }
        }
        
        
        $chatLine = new ChatLine();

        $form = $this->get('form.factory')->create(new NewChatLineType, $chatLine);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $chatLine->setIsClient(!$this->get('security.context')->isGranted('ROLE_ADMIN'));
            $chatLine->setCreatedAt(new \DateTime());

            $project->addChatLine($chatLine);

            $em->persist($chatLine);
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('sinenco_project_detail', ['id' => $id]));
        }

        return $this->render('SinencoProjectBundle:Front:detail.html.twig', array(
                    'project' => $project,
                    'form' => $form->createView()
        ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function chatAction(Request $request, $id) {
        
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $project = $repository->find($id);

        if ( $project->getUser() != $this->getUser() ){
            if ( ! $this->get('security.context')->isGranted('ROLE_ADMIN') ){
                return $this->redirect($this->generateUrl('sinenco_project_homepage'));
            }
        }
        
        
        $chatLine = new ChatLine();

        $form = $this->get('form.factory')->create(new NewChatLineType, $chatLine);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $chatLine->setIsClient(!$this->get('security.context')->isGranted('ROLE_ADMIN'));
            $chatLine->setCreatedAt(new \DateTime());

            $project->addChatLine($chatLine);

            $em->persist($chatLine);
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('sinenco_project_detail', ['id' => $id]));
        }

        return $this->render('SinencoProjectBundle:Front:detail.html.twig', array(
                    'project' => $project,
                    'form' => $form->createView()
        ));
    }

    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function documentAction(Request $request, $id) {
        
        $repository = $this->getDoctrine()->getManager()->getRepository('SinencoProjectBundle:Project');

        $project = $repository->find($id);

        if ( $project->getUser() != $this->getUser() ){
            if ( ! $this->get('security.context')->isGranted('ROLE_ADMIN') ){
                return $this->redirect($this->generateUrl('sinenco_project_homepage'));
            }
        }
        
        
        

        return $this->render('SinencoProjectBundle:Front:document.html.twig', array(
                    'project' => $project,
        ));
    }

    
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request) {


        $project = new Project();

        $form = $this->get('form.factory')->create(new NewProjectType, $project);

        $form->handleRequest($request);


        if ($form->isValid()) {
            $translator = $this->get('translator');

            $em = $this->getDoctrine()->getManager();

            $project->setUser($this->getUser());

            $project->setCreatedAt(new \DateTime());


            $chatLine = new ChatLine();
            $chatLine->setContent($translator->trans("sinenco.project.new.chatline_content"));
            $chatLine->setIsClient(false);
            $chatLine->setCreatedAt(new \DateTime());

            $project->addChatLine($chatLine);

            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('sinenco_project_homepage'));
        }

        return $this->render('SinencoProjectBundle:Front:new.html.twig', array('form' => $form->createView()));
    }

}
