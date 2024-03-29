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
    public function renewLicenseAction(Renting $renting) {
        $this->get('services_core.core_services')->renewLicense($renting);
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
            $rentingServiceId = $renting->getService()->getId();

            // La premiere ligne doit contenir le nombre de chaque ( success, danger, warning ) 
            if (!array_key_exists($rentingServiceId, $rentings)) {
                //Dans l'ordre [Success, Info, Warning, Danger]
                $rentings[$rentingServiceId][0] = [0, 0, 0, 0];
                $rentings[$rentingServiceId][1] = [];
                foreach ($renting->getDetails() as $detail) {
                    $detailName = $detail->getDetailName();
                    if ($detailName->getIsDisplayedOnList()) {
                        $rentings[$rentingServiceId][1][] = $detailName->translate($request->getLocale())->getName();
                    }
                }
            }

            $rentingServiceName = $renting->getService()->translate($request->getLocale())->getName();

            $rentings[$rentingServiceId][2] = [$rentingServiceName];

            $category = $renting->getService()->getCategory();

            while ($category != null) {
                $rentings[$rentingServiceId][2][] = $category->translate($request->getLocale())->getName();
                $category = $category->getParentCategory();
            }

            $rentings[$rentingServiceId][] = array();
            $i = sizeof($rentings[$rentingServiceId]) - 1;
            // Calcule pour rentings si on doit le mettre en success, danger, ou warning
            if (new DateTime(date("Y-m-d", time() ) ) > $renting->getExpiration()) {
                $rentings[$rentingServiceId][0][3] ++;
                $rentings[$rentingServiceId][$i]["state"] = "danger";
            } elseif (new DateTime(date("Y-m-d", time() + 3600 * 24 * 7)) > $renting->getExpiration()) {
                $rentings[$rentingServiceId][0][2] ++;
                $rentings[$rentingServiceId][$i]["state"] = "warning";
            } elseif (new DateTime(date("Y-m-d", time() + 3600 * 24 * 15)) > $renting->getExpiration()) {
                $rentings[$rentingServiceId][0][1] ++;
                $rentings[$rentingServiceId][$i]["state"] = "info";
            } else {
                $rentings[$rentingServiceId][0][0] ++;
                $rentings[$rentingServiceId][$i]["state"] = "success";
            }

            $rentings[$rentingServiceId][$i]["object"] = $renting;
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
            if ($this->checkIfLockedEdited($renting, $oldDetails) && $renting->getExpiration() == null) {
                $renting->setExpiration($oldExpiration);
                $em = $this->getDoctrine()->getManager();
                $em->persist($renting);
                $em->flush();

                /* Si un champ payant a été modifié, alors il faut faire un carteItem */

                $productCartItem = $this->get('shop_cart.cart')->addProductToCart($renting->getService()->getProduct()->getId(), null);

                $productCartItem->setFirstTime(false);

                $productCartItem->addConfiguration(true, CartItem::HIDE_FIELDS);
                $productCartItem->addConfiguration(true, CartItem::CHANGE_FIELDS);

                $totalPriceForConfiguration = 0;
                $daysDiff = $renting->getExpiration()->diff(new \DateTime());

                foreach ($renting->getDetails() as $key => $detail) {

                    $price = 0;

                    if ($oldDetails[$key] != $detail->getValue()) {
                        if ($detail->getDetailName()->getAttribute()->getType() == "checkbox") {
                            if ($detail->getValue() == "on") {
                                // On viens de selectionner une checkbox
                                $detailAttributeValues = $detail->getDetailName()->getAttribute()->getValues();
                                if (count($detailAttributeValues) > 0) {
                                    // Alors il y a un prix
                                    foreach ($detailAttributeValues as $val) {
                                        $price = $val->getPrice()->getMonthly() / 30 * $daysDiff->days;
                                        $totalPriceForConfiguration += $this->container->get('shop_core.currency')->convertPrice(
                                                $price, $val->getPrice()->getCurrency()->getCode()
                                        );
                                    }
                                }
                            }
                        } elseif ($detail->getDetailName()->getAttribute()->getType() == "choice") {
                            $detailAttributeValues = $detail->getDetailName()->getAttribute()->getValues();
                            foreach ($detailAttributeValues as $val) {
                                if ($oldDetails[$key] == $val->getCanonicalName()) {
                                    $price = $val->getPrice()->getMonthly() / 30 * $daysDiff->days;
                                    $price /= -2;
                                    $totalPriceForConfiguration += $this->container->get('shop_core.currency')->convertPrice(
                                            $price, $val->getPrice()->getCurrency()->getCode()
                                    );
                                }
                                if ($detail->getValue() == $val->getCanonicalName()) {
                                    $price = $val->getPrice()->getMonthly() / 30 * $daysDiff->days;
                                    $totalPriceForConfiguration += $this->container->get('shop_core.currency')->convertPrice(
                                            $price, $val->getPrice()->getCurrency()->getCode()
                                    );
                                }
                            }
                        }
                    }

                    $productCartItem->addOptionsValues(
                            $detail->getDetailName()->getAttribute()->getCanonicalName(), $detail->getValue());

                    $productCartItem->addConfiguration(null, CartItem::TYPE_FIELD);
                    $productCartItem->addConfiguration(null, CartItem::TYPE_FIELD, $detail->getDetailName()->getAttribute()->getCanonicalName());
                    $productCartItem->addConfiguration($detail->getValue(), CartItem::TYPE_FIELD, $detail->getDetailName()->getAttribute()->getCanonicalName(), CartItem::FIELD_VALUE);

                    if ($price > 0) {
                        $detail->setValue($oldDetails[$key]);
                    }
                }
                if ($totalPriceForConfiguration > 0) {
                    $productCartItem->addConfiguration(null, CartItem::TYPE_PRICE);
                    $productCartItem->addConfiguration(null, CartItem::TYPE_PRICE, CartItem::PRICE_TOTAL);
                    $productCartItem->addConfiguration(
                            $totalPriceForConfiguration, CartItem::TYPE_PRICE, CartItem::PRICE_TOTAL, CartItem::PRICE_TOTAL_AMOUNT
                    );
                    $productCartItem->addConfiguration(
                            $this->container->get('shop_core.currency')->getCurrency(), CartItem::TYPE_PRICE, CartItem::PRICE_TOTAL, CartItem::PRICE_TOTAL_CURRENCY
                    );

                    $productCartItem->addHiddenValues("renting", $renting->getId());

                    $em->persist($productCartItem);
                    $em->flush();
                    $this->get('shop_cart.cart')->flush();
                    
                    $request->getSession()->getFlashBag()->add('warning', $this->get('translator')->trans('services.renting_edited_partial'));
                    return $this->redirect($this->generateUrl('services_mine_edit', array('id' => $id)));
                    
                } else {
                    $this->get('shop_cart.cart')->removeItem($productCartItem->getId());
                    $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('services.renting_edited'));
                }


                $form = $this->get('form.factory')->create(new RentingType(), $renting);
            } else {
                $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans('services.renting_forbidden_fields'));
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
    public function addCartAction($id_renting) {


        $repository = $this->getDoctrine()->getManager()->getRepository('ServicesCoreBundle:Renting');
        $renting = $repository->findOneById($id_renting);

        if ($renting == null) {
            $response->setContent('-3');
            return $response;
        }
        $service = $renting->getService();

        $product = $service->getProduct();

        if ($product == null) {
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

    private function addConfigurationToCartItem($productCartItem, $renting) {
        $service = $renting->getService();
        $product = $service->getProduct();

        $productCartItem->setFirstTime(false);

        foreach ($renting->getDetails() as $key => $detail) {
            $productCartItem->addOptionsValues(
                    $detail->getDetailName()->getAttribute()->getCanonicalName(), $detail->getValue());

            $productCartItem->addConfiguration(null, CartItem::TYPE_FIELD);
            $productCartItem->addConfiguration(null, CartItem::TYPE_FIELD, $detail->getDetailName()->getAttribute()->getCanonicalName());
            $productCartItem->addConfiguration($detail->getValue(), CartItem::TYPE_FIELD, $detail->getDetailName()->getAttribute()->getCanonicalName(), CartItem::FIELD_VALUE);
        }

        $productCartItem->addHiddenValues("renting", $renting->getId());

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
