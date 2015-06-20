<?php

namespace Shop\ProductBundle\Controller;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;

class PurchasesAdminController extends Controller {

    public function editAction($id = null) {

        $retParent = parent::editAction($id);

        if (get_class($retParent) == "Symfony\Component\HttpFoundation\RedirectResponse") {
            // Alors l'object à bien été modifié


            $id = $this->get('request')->get($this->admin->getIdParameter());
            $purchase = $this->admin->getObject($id);
            $user = $purchase->getUser();
            // On envoi l'email
            $translator = $this->get('translator');
        
            $this
                    ->get('mail_service')
                    ->sendMail(
                            $this, 
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
