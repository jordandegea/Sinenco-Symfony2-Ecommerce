<?php

namespace Shop\CartBundle\Twig;

use Shop\CartBundle\Entity\CartItem,
    Shop\ProductBundle\Twig\ProductExtension,
    Shop\CoreBundle\Services\Currency;

class CartExtension extends \Twig_Extension {

    private $container;
    private $service;

    public function __construct($container) {
        $this->container = $container;
        $this->service = $container->get("shop_cart.cart");
        $this->serviceProduct = $container->get("shop_products");
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('findRowOnArrayWithCanonicalName', array($this, 'findRowOnArrayWithCanonicalName')),
        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('CartItemGetFormattedFieldPrice', array($this, 'CartItemGetFormattedFieldPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('displayPriceOption', array($this, 'displayPriceOption'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('displayOptionValue', array($this, 'displayOptionValue'), array('is_safe' => array('html'))),
        );
    }

    public function displayOptionValue($option, $optionValue) {
        foreach ($option->getValues() as $value) {
            if ($value->getCanonicalName() == $optionValue) {
                return $value;
            }
        }
    }

    public function displayPriceOption($item, $option) {

        $translator = $this->container->get('translator') ;
        
        if ($option->getPrice()->getOneTime() > 0) {
            $base = $this->serviceProduct->getFormattedPrice(
                    $this->container->get("shop_core.currency")->getCurrencyObject(), $option->getPrice()->getOneTime());
            
            $total = $this->service->calculateTotalPriceOption(
                    $option->getPrice(), $item->getPrices()
            );
            
            $total = $this->serviceProduct->getFormattedPrice(
                    $this->container->get("shop_core.currency")->getCurrencyObject(), $total);
            
            return $translator->trans("cart.option_price.one_time", ["%total%" => $total, "%base%" => $base] );
            
        } elseif ($option->getPrice()->getMonthly() > 0) {

            $base = $this->serviceProduct->getFormattedPrice(
                    $this->container->get("shop_core.currency")->getCurrencyObject(), $option->getPrice()->getMonthly());

            $total = $this->service->calculateTotalPriceOption(
                    $option->getPrice(), $item->getPrices()
            );
            
            $total = $this->serviceProduct->getFormattedPrice(
                    $this->container->get("shop_core.currency")->getCurrencyObject(), $total);
            
            return $translator->trans("cart.option_price.monthly", ["%total%" => $total, "%base%" => $base] );
        }
    }

    public function findRowOnArrayWithCanonicalName($array, $canonicalName) {
        foreach ($array as $row) {
            if (method_exists($row, 'getCanonicalName') && $row->getCanonicalName() == $canonicalName) {
                return $row;
            }
        }
        return null;
    }

    public function CartItemGetFormattedFieldPrice($field, $cartItem, $currencyOrQuantity = null, $currency = null) {

        $product = $cartItem->children["product"];

        $productObject = $product->vars["value"];

        $price = $productObject->getPrice();

        if ($currencyOrQuantity == null) {
            $currency = $this->serviceProduct->getCurrencyIfNotDefined($currency);
            $quantity = 1;
        } else {
            if (is_numeric($currencyOrQuantity)) {
                $currency = $this->serviceProduct->getCurrencyIfNotDefined($currency);
                $quantity = $currencyOrQuantity;
            } else {
                $currency = $currencyOrQuantity;
                $quantity = 1;
            }
        }


        $method = 'get' . ucfirst($field);


        if ($price != null && method_exists($price, $method)) {
            return $this->serviceProduct->getFormattedPrice($price->getCurrency(), $quantity * $price->$method());
        }

        return '';
    }

    public function getName() {
        return 'shop_cart_cartextension';
    }

}
