<?php

// src/Sinenco/UserBundle/Controller/RegistrationController.php

namespace Sinenco\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Shop\UserBundle\Entity\UserAddress;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Sinenco\UserBundle\Entity\User;

class RegistrationController extends BaseController {

    public function registerAction(Request $request) {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                //$url = $this->generateUrl('fos_user_registration_confirmed');
                $translator = $this->get('translator');

                $texteTraduit = $translator->trans('registration.confirmed', array(
                    '%username%' => $user->getUsername()
                        ), 'FOSUserBundle');
                $request->getSession()->getFlashBag()->add('success', $texteTraduit);
                $url = $this->generateUrl('sinenco_core_profile');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
                    'form' => $form->createView(),
                    'separator' => array("plainPassword")
        ));
    }


}
