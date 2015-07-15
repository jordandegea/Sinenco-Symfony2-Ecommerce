<?php

namespace Shop\CartBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Shop\CartBundle\Entity\Cart as CartEntity;
use Shop\CartBundle\Entity\CartItem as CartItemEntity;
use Shop\ProductBundle\Entity\Product;
use Shop\CartBundle\Entity\CartItemPrices as CartItemPricesEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Shop\PaymentBundle\Entity\Invoice,
    Shop\PaymentBundle\Entity\InvoiceLine,
    Shop\PaymentBundle\Entity\InvoiceLineOption;

class CartService {

    const MAX_TIME = 12960000; // 15 jours

    protected $container;
    protected $response;
    protected $em;
    protected $cartId;
    protected $cartStep;
    protected $cart;

    /**
     * Return the currency choosen
     * @return string
     */
    public function onKernelRequest(GetResponseEvent $event) {
        if ($this->em->isOpen() == false) {
            return;
        }
        $response = $event->getResponse();
        $request = $event->getRequest();
        $this->response = $response;
        $this->request = $request;
        $this->checkIfCartId($request, $response);
    }

    public function getCartId() {
        return $this->cartId;
    }

    public function getCart() {
        return $this->cart;
    }

    public function transformInvoice() {

        $cart = $this->getCart();
        $cart->setComplete(true);

        $this->em->persist($cart);
        $this->em->flush();

        $this->findOrCreateCart();
    }

    public function getTotalPriceHT($currency = null) {

        $currency = $this->getCurrencyIfNotDefined($currency);

        $totalPrice = 0;

        $cartItems = $this->cart->getProducts();

        foreach ($cartItems as $cartItem) {
            $price = $cartItem->getProduct()->getPrice();

            $totalPrice += $this->getTotalOfPrice($price, $cartItem);

            $totalPrice += $this->getTotalOptionsPriceHT(
                    $cartItem, $cartItem->getProduct()->getOptions(), $cartItem->getOptionsValues()
            );
        }

        return $totalPrice;
    }

    private function getTotalOptionsPriceHT($cartItem, $options, $optionsValues) {
        $totalPrice = 0;
        /* On parcours les options */
        foreach ($options as $option) {
            /* Si l'option existe dans les valeurs d'options */
            if (array_key_exists($option->getCanonicalName(), $optionsValues)) {
                /* On parcourt les valeurs pour trouver la bonne */
                foreach ($option->getValues() as $value) {
                    if ($value->getCanonicalName() == $optionsValues[$option->getCanonicalName()]) {
                        /* On a trouvé l'option */
                        $totalPrice += $this->calculateTotalPriceOption($value->getPrice(), $cartItem->getPrices());
                        break;
                    }
                    if ($option->getType() == "checkbox") {
                        if ($optionsValues[$option->getCanonicalName()] == "on") {
                            $totalPrice += $this->calculateTotalPriceOption($value->getPrice(), $cartItem->getPrices());
                        }
                        break;
                    }
                }
            }
        }

        return $totalPrice;
    }

    public function calculateTotalPriceOption($priceOption, $priceQuantity) {
        if ($priceOption == null) {
            return;
        }
        $total = 0;

        if ($priceOption->getMonthly() == 0) {
            if (
                    $priceQuantity->getOneTime() == 0 AND
                    $priceQuantity->getMonthly() OR
                    $priceQuantity->getQuarterly() OR
                    $priceQuantity->getSemiannually() OR
                    $priceQuantity->getAnnually()
            ) {
                $total += $priceOption->getOneTime();
            } else {
                $total += $priceOption->getOneTime() * $priceQuantity->getOneTime();
            }
        } else {
            $total += $priceOption->getMonthly() * $priceQuantity->getMonthly();
            $total += $priceOption->getQuarterly() * $priceQuantity->getQuarterly();
            $total += $priceOption->getSemiannually() * $priceQuantity->getSemiannually();
            $total += $priceOption->getAnnually() * $priceQuantity->getAnnually();
        }

        $total = $this->container->get('shop_core.currency')->convertPrice(
                $total, $priceOption->getCurrency()->getCode()
        );

        return $total;
    }

    public function getTotalOfPrice($price, $cartItem) {
        $totalPrice = 0;
        foreach (CartItemPricesEntity::$listFunctionGetPrices as $priceMethod) {
            $totalPrice += $this->container->get('shop_core.currency')->convertPrice(
                    $price->$priceMethod['function']() * $cartItem->getPrices()->$priceMethod['function'](), $price->getCurrency()->getCode()
            );
        }
        return $totalPrice;
    }

    public function getAllProductsInformationForTop() {
        $products = array();

        $cartItems = $this->cart->getProducts();

        foreach ($cartItems as $cartItem) {
            $name = $cartItem->getProduct()->translate($this->request->getLocale())->getName();
            $quantity = $cartItem->getPrices()->getOneTime();
            if ($quantity == 0) {
                $quantity = "";
            }
            $products[] = ['name' => $name, 'quantity' => $quantity];
        }

        return $products;
    }

    public function getCartStep() {
        return $this->cartStep;
    }

    public function setCartStep($step, $force = false) {
        if ($force) {
            $this->cartStep = $step;
            $this->setCookie('cartStep', $this->cartStep, $this->response);
        } else {
            if ($this->cartStep < $step) {
                $this->cartStep = $step;
                $this->setCookie('cartStep', $this->cartStep, $this->response);
            }
        }
    }

    public function getNumberItems() {
        return count($this->cart->getProducts());
    }

    public function removeItem($id) {

        $cartItem = $this
                ->em
                ->getRepository('ShopCartBundle:CartItem')
                ->find($id);
        if ($cartItem != null) {
            $this->cart->removeProduct($cartItem);
            $this->em->flush();
        }
    }

    public function addProductToCart($product_id, $configuration = null) {

        $product = $this
                ->em
                ->getRepository('ShopProductBundle:Product')
                ->find($product_id);

        if ($product == null) {
            return null;
        }

        $cartItem = new CartItemEntity();
        $cartItem->setProduct($product); // On lie le produit à l'item
        // Create and define Prices of the CartItem
        $cartItemPrices = new CartItemPricesEntity;
        if ($product->getPrice()->getOneTime() > 0) {
            $cartItemPrices->setOneTime(1);
            $cartItemPrices->setMonthly(0);
        } else {
            $cartItemPrices->setOneTime(0);
            $cartItemPrices->setMonthly(1);
        }
        $cartItemPrices->setQuarterly(0);
        $cartItemPrices->setSemiannually(0);
        $cartItemPrices->setAnnually(0);

        //Add their Prices to the Cart
        $cartItem->setPrices($cartItemPrices);

        // Add the Item at the Cart
        $this->cart->addProduct($cartItem); // On ajoute un item

        $this->em->persist($cartItem); // n persist l'item

        $this->em->flush();

        $this->addOptionsOnCartItemIfNotExist($cartItem);

        if ($configuration != null) {
            $this->addConfigurationToCartItem($cartItem, $configuration);
        }

        return $cartItem;
    }

    public function addOptionsOnCartItemsIfNotExist($cartItems) {
        foreach ($cartItems as $cartItem) {
            $this->addOptionsOnCartItemIfNotExist($cartItem);
        }
        //$this->getDoctrine()->getManager()->persist($cartItems); // Don't need a persist
        $this->em->flush();
    }

    public function flush() {
        $this->em->flush();
    }

    public function addOptionsOnCartItemIfNotExist($cartItem) {

        $productOptions = $cartItem->getProduct()->getOptions();
        if ($cartItem->getOptionsValues() == null) {
            foreach ($productOptions as $productOption) {
                $cartItem->addOptionsValues($productOption->getCanonicalName(), '');
            }
        } else {
            foreach ($productOptions as $productOption) {
                if (!array_key_exists($productOption->getCanonicalName(), $cartItem->getOptionsValues())) {
                    $cartItem->addOptionsValues($productOption->getCanonicalName(), '');
                }
            }
        }
    }

    public function addConfigurationToCartItem($cartItem, $configuration) {
        
    }

    private function checkIfCartId($request, $response) {
        $this->cartId = $request->cookies->get('cartId');

        if ($this->cartId == null) {
            // Nous n'avons pas de panier dans les cookies
            $this->findOrCreateCart();
        } else {
            $this->cart = $this
                    ->em
                    ->getRepository('ShopCartBundle:Cart')
                    ->find($this->cartId);

            if ($this->cart == null) {
                $this->findOrCreateCart();
            } else {
                $this->cartId = $this->cart->getId();
                // il fautdrai tverifier si le cart appartien tau user ou pas. 
            }
        }

        $this->cartStep = $request->cookies->get('cartStep');

        if ($this->cartStep == null) {
            $this->setCookie('cartStep', 1, $response);
            $this->cartStep = 1;
        }
    }

    private function findOrCreateCart() {
        $token = $this->container->get('security.context')->getToken();

        if ($token != null && gettype($token->getUser()) !== "string") {
            // Nous somme loggé, on va chercher un panier existant dans la BDD

            $this->cart = $this
                    ->em
                    ->getRepository('ShopCartBundle:Cart')
                    ->findOneBy(
                    array(
                'user' => $token->getUser(),
                'complete' => false
                    ), array('id' => 'ASC')
            );

            if ($this->cart != null) {
                $this->cartId = $this->cart->getId();
            } else {
                $this->createNewCart($token->getUser());
            }
        } else {
            // on est pas loggé
            $this->createNewCart(null);
        }


        $this->cartStep = 1;

        $this->setCookie('cartId', $this->cartId, $this->response);
        $this->setCookie('cartStep', $this->cartStep, $this->response);
    }

    private function setCookie($name, $value, $response) {
        $cookie = new Cookie(
                $name, $value, time() + self::MAX_TIME);
        if ($response == null) {
            $response = new Response;
            $response->headers->setCookie($cookie);
            $response->send();
        } else {
            $response->headers->setCookie($cookie);
        }
    }

    private function createNewCart($user = null) {

        $manager = $this->em;

        $this->cart = new CartEntity();

        $manager->persist($this->cart);
        $manager->flush();

        $this->cartId = $this->cart->getId();
    }

    public function updateUserCart($user = null) {

        if ($this->cart->getUser() == null) {
            $this->cart->setUser($user);

            $this->em->persist($this->cart);
            $this->em->flush();
        }
    }

    public function __construct(EntityManager $entityManager, Container $container) {

        $this->em = $entityManager;
        $this->container = $container;
    }

    private function getCurrencyIfNotDefined($currency) {
        if ($currency == null) {
            return $this->container->get('shop_core.currency')->getCurrency();
        }
        return $currency;
    }

    public function createInvoiceWithCart(CartEntity $cart) {

        $invoice = new Invoice();

        $translator = $this->container->get('translator');

        // On ajoute 0 à la facture car il n'y a aucune transaction pour le moment
        $invoice->setNumber(0);

        $invoice->setCredit(0.00);

        $invoice->setTotalPrice($this->getTotalPriceHT());

        $invoice->setAddressReceiver($cart->getBillingAddress()->__toString());

        $invoice->setAddressSender($this->container->get('twig')->getGlobals()['invoice']['sender_address']);

        $invoice->setCurrency($this->container->get('shop_core.currency')->getCurrencyObject());

        $invoice->setCart($cart);

        $invoice->setUser($cart->getUser());

        $invoice->setDate(new \DateTime);

        foreach ($cart->getProducts() as $cartItem) {
            $invoiceLine = new InvoiceLine();

            $product = $cartItem->getProduct();

            $invoiceLine->setName(
                    $product->translate()->getName());

            $prices = $cartItem->getPrices();
            if ($prices->getOneTime() == 0) {
                if ($prices->getMonthly() != 0) {
                    $price = $totalPrice += $this->container->get('shop_core.currency')->convertPrice(
                            $product->getPrice()->getMonthly()* $prices->getMonthly(), $product->getPrice()>getCurrency()->getCode()
                    );
                    $invoiceLine->addOption(
                            $this->createNewInvoiceLineOption(
                                    $translator->trans('cart.invoice.monthly', ["%number%" => $prices->getMonthly()]), $price
                            )
                    );
                }
                if ($prices->getQuarterly() != 0) {
                    $price = $totalPrice += $this->container->get('shop_core.currency')->convertPrice(
                            $product->getPrice()->getQuarterly() * $prices->getQuarterly(), $product->getPrice()>getCurrency()->getCode()
                    );
                    $invoiceLine->addOption(
                            $this->createNewInvoiceLineOption(
                                    $translator->trans('cart.invoice.quarterly', ["%number%" => $prices->getQuarterly()]), $price
                            )
                    );
                }
                if ($prices->getSemiannually() != 0) {
                    $price = $totalPrice += $this->container->get('shop_core.currency')->convertPrice(
                            $product->getPrice()->getSemiannually() * $prices->getSemiannually(), $product->getPrice()>getCurrency()->getCode()
                    );
                    $invoiceLine->addOption(
                            $this->createNewInvoiceLineOption(
                                    $translator->trans('cart.invoice.semiannually', ["%number%" => $prices->getSemiannually()]), $price
                            )
                    );
                }
                if ($prices->getAnnually() != 0) {
                    $price = $totalPrice += $this->container->get('shop_core.currency')->convertPrice(
                            $product->getPrice()->getAnnually() * $prices->getAnnually(), $product->getPrice()>getCurrency()->getCode()
                    );
                    $invoiceLine->addOption(
                            $this->createNewInvoiceLineOption(
                                    $translator->trans('cart.invoice.annually', ["%number%" => $prices->getAnnually()]), $price
                            )
                    );
                }
                $invoiceLine->setQuantity(1);
            } else {
                $invoiceLine->setQuantity($prices->getOneTime());
            }

            $optionsValues = $cartItem->getOptionsValues();
            foreach ($product->getOptions() as $option) {
                if (!array_key_exists($option->getCanonicalName(), $optionsValues)) {
                    continue;
                }
                if ($option->getType() == "checkbox") {
                    if ($optionsValues[$option->getCanonicalName()] == "on") {
                        $name = $option->translate()->getFieldName();
                        if (count($option->getValues()) > 0) {
                            $price = $this->calculateTotalPriceOption($option->getValues()[0]->getPrice(), $cartItem->getPrices());
                        } else {
                            $price = 0;
                        }
                    }
                } elseif ($option->getType() == "choice") {
                    if (empty($optionsValues[$option->getCanonicalName()])) {
                        continue;
                    }
                    $name = $option->translate()->getFieldName() . " : ";
                    $price = 0;
                    foreach ($option->getValues() as $value) {
                        if ($value->getCanonicalName() == $optionsValues[$option->getCanonicalName()]) {
                            $name .= $value->translate()->getValue();
                            $price = $this->calculateTotalPriceOption($value->getPrice(), $cartItem->getPrices());
                            break;
                        }
                    }
                } elseif ($option->getType() == "textfield") {
                    if (empty($optionsValues[$option->getCanonicalName()])) {
                        continue;
                    }
                    $name = $option->translate()->getFieldName() . " : " . $optionsValues[$option->getCanonicalName()];
                    $price = 0;
                }
                $invoiceLine->addOption(
                        $this->createNewInvoiceLineOption(
                                $name, $price
                        )
                );
            }
            $price = $product->getPrice();

            $totalPrice = $this->getTotalOfPrice($price, $cartItem);
            $totalPrice += $this->getTotalOptionsPriceHT(
                    $cartItem, $cartItem->getProduct()->getOptions(), $cartItem->getOptionsValues()
            );

            $invoiceLine->setUnitPrice($totalPrice);

            $invoice->addLine($invoiceLine);
        }

        $invoice->setTotalPriceEUR(0);
        return $invoice;
    }

    private function createNewInvoiceLineOption($name, $unitPrice) {

        $invoiceLineOption = new InvoiceLineOption();
        $invoiceLineOption->setName($name);
        $invoiceLineOption->setUnitPrice($unitPrice);
        return $invoiceLineOption;
    }

}
