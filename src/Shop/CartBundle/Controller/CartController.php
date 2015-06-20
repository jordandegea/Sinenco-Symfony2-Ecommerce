<?php

namespace Shop\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Shop\CartBundle\Form\CartType;
use Shop\CartBundle\Form\CartAddressesType;
use Shop\PaymentBundle\Entity\Order;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CartController extends Controller {

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function checkoutAction(Request $request) {

        $cart = $this->get('shop_cart.cart')->getCart();

        if (!$this->isFieldsCheckoutValid($cart)) {
            $request->getSession()->getFlashBag()->add('warning', $this->get('translator')->trans('cart.warning.step_checkout_cart_fields'));
            return $this->redirect($this->generateUrl('shop_cart_view'));
        }

        if ($cart->getBillingAddress() == null) {
            $request->getSession()->getFlashBag()->add('warning', $this->get('translator')->trans('cart.warning.step_checkout_addresses'));
            return $this->redirect($this->generateUrl('shop_cart_addresses'));
        }
        $this->get('shop_cart.cart')->updateUserCart($this->getUser()); // Pour que l'id soit bien avec le user
        $this->get('shop_cart.cart')->setCartStep(4);


        $formBuilder = $this->get('form.factory')->createBuilder('form', $cart);


        $formBuilder
                ->add('comment', 'textarea')
        ;

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            
            $invoice = $this->get('invoicing')->createInvoiceWithCart($cart);
            
            $em->persist($invoice);
            $em->flush();

            return $this->redirect($this->generateUrl('shop_payment_invoice', array('id' => $invoice->getId())));
        }
        return $this->render('ShopCartBundle:Cart:checkout.html.twig', array(
                    'cart' => $cart,
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function addressesAction(Request $request) {
        if ($this->get('shop_cart.cart')->getNumberItems() <= 0) {
            return $this->redirect($this->generateUrl('shop_cart_view'));
        }

        $this->get('shop_cart.cart')->setCartStep(3);

        $cart = $this->get('shop_cart.cart')->getCart();

        $addressesForm = $this->addressesForm($request, $cart);

        $cartAddress = $cart->getBillingAddress();

        if ($addressesForm == false) {
            return $this->redirect($this->generateUrl('shop_cart_checkout'));
        }

        return $this->render('ShopCartBundle:Cart:addresses.html.twig', array(
                    'form' => $addressesForm,
                    'cartAddress' => $cartAddress
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function identificationAction() {

        if ($this->get('shop_cart.cart')->getNumberItems() <= 0) {
            return $this->redirect($this->generateUrl('shop_cart_view'));
        }

        // Si on est la c'est qu'on est logué
        return $this->redirect($this->generateUrl('shop_cart_addresses'));

        // Je laisse la suite si on veut faire autre chose

        $loginPath = $this->generateUrl('fos_user_security_login');
        $registerPath = $this->generateUrl('fos_user_registration_register');

        $this->get('shop_cart.cart')->setCartStep(2);

        return $this->render('ShopCartBundle:Cart:identification.html.twig', array(
                    'loginPath' => $loginPath,
                    'registerPath' => $registerPath
        ));
    }

    public function viewAction(Request $request) {
        $cart = $this->get('shop_cart.cart')->getCart();

        $cartItems = $cart->getProducts();
        $this->get('shop_cart.cart')->addOptionsOnCartItemsIfNotExist($cartItems);


        $form = $this->get('form.factory')->create(new CartType, $cart);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $isCheckoutClicked = $form->get('next_step')->isClicked();

            $this->cartFormValidation($cart);
            $form = $this->get('form.factory')->create(new CartType, $cart);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            if ($isCheckoutClicked) {
                if ($this->get('shop_cart.cart')->getNumberItems() > 0) {
                    $this->get('shop_cart.cart')->setCartStep(2);
                    return $this->redirect($this->generateUrl('shop_cart_identification'));
                }
            }
            return $this->redirect($this->generateUrl('shop_cart_view'));
        }

        $this->get('shop_cart.cart')->setCartStep(1);

        return $this->render('ShopCartBundle:Cart:view.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function removeItemAction($id) {
        $this->get('shop_cart.cart')->removeItem($id);
        return $this->redirect($this->generateUrl('shop_cart_view'));
    }

    public function addToCartAction(Request $request, $product_id = null) {

        //Il faudrait verifier si on peut ajouter le produit au panier ou pas. 

        $response = new Response;

        if ($product_id == null) {
            $response->setContent('-1');
        } else {
            $productCartItem = $this->get('shop_cart.cart')->addProductToCart($product_id, null);
            if ($productCartItem == null) {
                $response->setContent('-2');
            } else {
                $response->setContent($productCartItem->getId());
            }
        }

        return $response;
    }

    /* Private functions */

    private function cartFormValidation($cart) {

        foreach ($cart->getProducts() as $cartItem) {
            // On commence par vérifier si on a mis à 0 une quantité
            $prices = $cartItem->getPrices();
            if ($prices->getAnnually() + $prices->getSemiannually() + $prices->getQuarterly() + $prices->getMonthly() + $prices->getOneTime() < 1) {
                // on doit la supprimer
                $cart->removeProduct($cartItem);
            }
        }
    }

    private function addressesForm($request, $cart) {

        //User existe forcement
        $user = $this->getUser();
        $addresses = $user->getUserAddresses();

        $associations = 0;
        if ($request->request->has('billingAddress')) {
            foreach ($addresses as $address) {
                if ($address->getId() == $request->request->get('billingAddress')) {
                    $cart->setBillingAddress($address);
                    $associations = 1;
                    break;
                }
            }
            if ($associations == 1) {
                // a on bien select 2 adresses
                $em = $this
                        ->getDoctrine()
                        ->getManager();
                $em->persist($cart);
                $em->flush();
                return false;
            }
        }
        // On a pas associé, donc on affiche le "form"
        $form = array();
        $form['addresses'] = $addresses;

        return $form;
    }

    private function isFieldsCheckoutValid($cart = null) {
        if ($cart == null) {
            $cart = $this->getCart();
        }

        foreach ($cart->getProducts() as $cartItem) {
            $cartItemOptions = $cartItem->getOptionsValues();

            foreach ($cartItem->getProduct()->getOptions() as $option) {
                if (isset($cartItemOptions[$option->getCanonicalName()])) {
                    $cartItemOption = $cartItemOptions[$option->getCanonicalName()];
                    if ($option->getRequired() && $cartItemOption == "") {
                        return false;
                    }
                }
            }
        }

        return true;
    }

}
