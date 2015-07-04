<?php

namespace Services\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Services\CoreBundle\Form\RentingType;
use Symfony\Component\HttpFoundation\Response;
use Shop\CartBundle\Entity\CartItem,
    Services\CoreBundle\Entity\Renting;

class ServicesController extends Controller {

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function renewLicenseAction(Renting $renting){
        $this->get('services_core.core_services')->renewLicense($renting) ;
        return $this->redirect($this->generateUrl('services_mine_list'));
    }
    
    
    public function homepageAction(Request $request) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ServicesCoreBundle:Service');
        $servicesTemp = $repository->findAll();

        $services = array();

        foreach ($servicesTemp as $service) {
            if (!array_key_exists($service->getCategory()->getId(), $services)) {
                $category = $service->getCategory();
                while ($category != null) {
                    $services[$service->getCategory()->getId()][0][] = $category->translate($request->getLocale())->getName();
                    $category = $category->getParentCategory();
                }
            }
            $services[$service->getCategory()->getId()][] = $service;
        }

        $service = ksort($services);

        return $this->render('ServicesCoreBundle::homepage.html.twig', array(
                    'services' => $services
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function mineListAction(Request $request) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ServicesCoreBundle:Renting');
        $rentingstemp = $repository->findByUser($user = $this->getUser());

        $rentings = array();

        foreach ($rentingstemp as $renting) {
            $rentingServiceName = $renting->getService()->translate($request->getLocale())->getName();
            
            // La premiere ligne doit contenir le nombre de chaque ( success, danger, warning ) 
            if (!array_key_exists($rentingServiceName, $rentings)) {
                //Dans l'ordre [Success, Info, Warning, Danger]
                $rentings[$rentingServiceName][0] = [0, 0, 0, 0];
                $rentings[$rentingServiceName][1] = [];
                foreach ($renting->getDetails() as $detail) {
                    $detailName = $detail->getDetailName();
                    if ($detailName->getIsDisplayedOnList()) {
                        $rentings[$rentingServiceName][1][] = $detailName->translate($request->getLocale())->getName();
                    }
                }
            }
            
            $rentings[$rentingServiceName][2] = [] ;
            $category = $renting->getService()->getCategory(); 
            while($category != null ){
                $rentings[$rentingServiceName][2][] = $category->translate($request->getLocale())->getName();
                $category = $category->getParentCategory() ; 
            }

            $rentings[$rentingServiceName][] = array();
            $i = sizeof($rentings[$rentingServiceName]) - 1;
            // Calcule pour rentings si on doit le mettre en success, danger, ou warning
            if (new DateTime(date("Y-m-d", time() + 3600 * 24 * 7)) > $renting->getExpiration()) {
                $rentings[$rentingServiceName][0][3] ++;
                $rentings[$rentingServiceName][$i]["state"] = "danger";
            } elseif (new DateTime(date("Y-m-d", time() + 3600 * 24 * 15)) > $renting->getExpiration()) {
                $rentings[$rentingServiceName][0][2] ++;
                $rentings[$rentingServiceName][$i]["state"] = "warning";
            } elseif (new DateTime(date("Y-m-d", time() + 3600 * 24 * 30)) > $renting->getExpiration()) {
                $rentings[$rentingServiceName][0][1] ++;
                $rentings[$rentingServiceName][$i]["state"] = "info";
            } else {
                $rentings[$rentingServiceName][0][0] ++;
                $rentings[$rentingServiceName][$i]["state"] = "success";
            }

            $rentings[$rentingServiceName][$i]["object"] = $renting;
        }


        return $this->render('ServicesCoreBundle:Mine:list.html.twig', array(
                    'rentingsGrouped' => $rentings
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function mineEditAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getManager()->getRepository('ServicesCoreBundle:Renting');
        $renting = $repository->findOneById($id);

        if ($renting == null || $renting->getUser() != $this->getUser()) {
            return $this->redirect($this->generateUrl('services_mine_list'));
        }

        $oldExpiration = $renting->getExpiration();
        $oldDetails = array();
        foreach ($renting->getDetails() as $key => $value) {
            $oldDetails[$key] = $value->getValue();
        }

        $form = $this->get('form.factory')->create(new RentingType(), $renting);

        // il faudrait verifier l'expiration en plus 
        if ($form->handleRequest($request)->isValid()) {
            if ($this->checkIfLockedEdited($renting, $oldDetails) && $renting->getExpiration() == null ) {
                $renting->setExpiration($oldExpiration) ;
                $em = $this->getDoctrine()->getManager();
                $em->persist($renting);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('services.renting_edited'));
            } else {
                $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans('services.renting_forbidden_fields') );
                return $this->redirect($this->generateUrl('services_mine_edit', array('id' => $id)));
            }
        }

        return $this->render('ServicesCoreBundle:Mine:edit.html.twig', array(
                    'form' => $form->createView(),
                    'renting' => $renting
        ));
    }
    
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function addCartAction($id_renting){
        
        
        $repository = $this->getDoctrine()->getManager()->getRepository('ServicesCoreBundle:Renting');
        $renting = $repository->findOneById($id_renting);
        
        if ( $renting == null ){
            $response->setContent('-3');
            return $response;
        }
        $service = $renting->getService();
        
        $product = $service->getProduct();
        
        if ( $product == null ){
            $response->setContent('-4');
            return $response;
        }
        
        $product_id = $product->getId();
        
        $response = new Response;

        if ($product_id == null) {
            $response->setContent('-1');
        } else {
            $productCartItem = $this->get('shop_cart.cart')->addProductToCart($product_id, null);
            if ($productCartItem == null) {
                $response->setContent('-2');
            } else {
                $response->setContent($productCartItem->getId());
                $this->addConfigurationToCartItem($productCartItem, $renting);
            }
        }

        return $response;
        
        
    }
    
    private function addConfigurationToCartItem($productCartItem, $renting){
        $service = $renting->getService() ;
        $product = $service->getProduct();
        
        $productCartItem->setFirstTime(false);
        
        foreach ( $renting->getDetails() as $key => $detail ){
            $productCartItem->addOptionsValues(
                    $detail->getDetailName()->getAttribute()->getCanonicalName(), 
                    $detail->getValue()) ;
            
            $productCartItem->addConfiguration(null, CartItem::TYPE_FIELD);
            $productCartItem->addConfiguration($detail->getValue(), 
                    CartItem::TYPE_FIELD, 
                    $detail->getDetailName()->getId(), 
                    CartItem::FIELD_VALUE);
        }
        
        $productCartItem->addHiddenValues( "renting", $renting->getId() );
        
        $this->get('shop_cart.cart')->flush();
    }
    
    /**
     * 
     * @param type $renting
     * @param type $oldDetails
     * @return boolean
     * Retourne true si aucun champ interdit n'a été modifié
     */
    private function checkIfLockedEdited($renting, $oldDetails) {

        //Pour chaque detail, on verifie
        foreach ($renting->getDetails() as $detail) {
            if (!($detail->getDetailName()->getIsEditable()) && ( $detail->getValue() != $oldDetails[0])) {
                return false;
            }
        }

        return true;
    }

}
