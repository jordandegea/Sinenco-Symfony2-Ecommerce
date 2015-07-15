<?php

namespace Shop\ProductBundle\Controller;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request ;

class PurchasesAdminController extends Controller {
    
    public function editAction($id = null, Request $request = NULL) {

        $retParent = parent::editAction($id, $request);

        if (get_class($retParent) == "Symfony\Component\HttpFoundation\RedirectResponse") {
            // Alors l'object à bien été modifié


            $id = $request->get($this->admin->getIdParameter());
            $purchase = $this->admin->getObject($id);
            $user = $purchase->getUser();
            // On envoi l'email
            $translator = $this->get('translator');
        
            $this
                    ->get('mail_service')
                    ->sendMail(
                            $this->container, 
                            $translator->trans('purchase.email.edited.subject'), 
                            $user->getEmail(), 
                            "ShopProductBundle:Mails:purchase_edited.text.twig", 
                            array('purchase'=>$purchase), 
                            true, 
                            "ShopProductBundle:Mails:purchase_edited.html.twig", 
                            true
            );
        }
        return $retParent;
    }

}
