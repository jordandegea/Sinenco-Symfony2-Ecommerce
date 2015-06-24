<?php

namespace Sinenco\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sinenco\CoreBundle\Form\ProfileEditType;
use Shop\UserBundle\Entity\UserAddress;
use Shop\UserBundle\Form\UserAddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sinenco\CoreBundle\Form\ProfileType;

class CoreController extends Controller {

    const CURRENCY_CHANGE_COMMISSION = 0.99 ; 
    
    
    public function newsAction(Request $request) {
        
        return $this->render('SinencoCoreBundle:Core:news.html.twig', array(
        ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function profileAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();

        $user = $em->getRepository('SinencoUserBundle:User')->find($id);
        $userAddresses = $user->getUserAddress();
        
        $oldCurrency = $user->getCurrency();
        
        $formUser = $this->get('form.factory')->createNamed(
                'user', new ProfileEditType($this->get('service_container')), $user);

        $formUser->handleRequest($request);

        
        if ($formUser->isValid()) {
            
            $user->setBalance(
                    $this->get("shop_core.currency")->convertPrice(
                            $user->getBalance(), $oldCurrency, $user->getCurrency()
                    ) * self::CURRENCY_CHANGE_COMMISSION
            );

            $em->persist($user);
            $em->flush();
            
            $url = $this->get('router')->generate('sinenco_core_profile');
            return $this->redirect($url);
            
        }
        



        $formsUserAddress = array();
        for ($i = 0; $i < count($userAddresses); $i++) {
            $formsUserAddress[$i] = $this->get('form.factory')->createNamed(
                    'useraddress_' . $i, new UserAddressType(), $userAddresses[$i]);
            $formsUserAddress[$i]->handleRequest($request);
            if ($formsUserAddress[$i]->isValid()) {
                $em->persist($userAddresses[$i]);
                $em->flush();
            }
            $formsUserAddress[$i] = $formsUserAddress[$i]->createView();
        }

        return $this->render('SinencoCoreBundle:Core:edit.html.twig', array(
                    'formUser' => $formUser->createView(),
                    'formsUserAddresses' => $formsUserAddress,
                    'separator' => array('companyName', 'country', 'plainPassword')
        ));
    }

    public function addAddressAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $id = $this->getUser()->getId();

        $user = $em->getRepository('SinencoUserBundle:User')->find($id);

        $newUserAddress = new UserAddress();
        $user->addUserAddress($newUserAddress);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('sinenco_core_profile'));
    }

    public function imprintAction() {
        return $this->render('SinencoCoreBundle:Core:imprint.html.twig');
    }

}
