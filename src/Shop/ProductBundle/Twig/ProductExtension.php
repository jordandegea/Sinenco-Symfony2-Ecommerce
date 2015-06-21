<?php

// src/Shop/ProductBundle/Twig/ProductExtension.php

namespace Shop\ProductBundle\Twig;

use Shop\CoreBundle\Entity\Currencies;
use Shop\ProductBundle\Entity\Prices;

class ProductExtension extends \Twig_Extension {

    /**
     * @var ContainerInterface
     */
    private $container;
    private $service;
    private $listFunctionGetPrices = [
        ["function" => "getOneTime", "suffixPrice" => ""],
        ["function" => "getMonthly", "suffixPrice" => "calendar.month"],
        ["function" => "getQuarterly", "suffixPrice" => "calendar.quarter"],
        ["function" => "getSemiannually", "suffixPrice" => "calendar.semiannual"],
        ["function" => "getAnnually", "suffixPrice" => "calendar.year"]
    ];

    public function __construct($container) {
        $this->container = $container;
        $this->service = $container->get("shop_products");
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('getFirstPrice', array($this, 'getFirstPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('getFieldPrice', array($this, 'getFieldPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('FormatPrice', array($this, 'FormatPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('getFormattedFieldPrice', array($this, 'getFormattedFieldPrice'), array('is_safe' => array('html'))),
        );
    }

    public function getFieldPrice($price, $field, $currencyOrQuantity = null, $currency = null) {

        if ($currencyOrQuantity == null) {
            $currency = $this->service->getCurrencyIfNotDefined($currency);
            $quantity = 1;
        } else {
            if (is_numeric($currencyOrQuantity)) {
                $currency = $this->service->getCurrencyIfNotDefined($currency);
                $quantity = $currencyOrQuantity;
            } else {
                $currency = $currencyOrQuantity;
                $quantity = 1;
            }
        }

        $method = 'get' . ucfirst($field);

        if ($price != null && method_exists($price, $method)) {
            return $quantity * $price->$method();
        }

        return '';
    }

    public function FormatPrice($price, $currency = null) {

        $currency = $this->service->getCurrencyIfNotDefined($currency);
        $em = $this->container->get('doctrine')->getManager();


        $currencyObject = $em
                ->getRepository('ShopCoreBundle:Currencies')
                ->findOneByCode($currency);

        if ($currencyObject == null) {
            $currencyObject = $em
                    ->getRepository('ShopCoreBundle:Currencies')
                    ->findAll()[0];
        }

        return $this->service->getFormattedPrice($currencyObject, $price);
    }

    public function getFormattedFieldPrice($price, $field, $currencyOrQuantity = null, $currency = null) {

        if ($currencyOrQuantity == null) {
            $currency = $this->service->getCurrencyIfNotDefined($currency);
            $quantity = 1;
        } else {
            if (is_numeric($currencyOrQuantity)) {
                $currency = $this->service->getCurrencyIfNotDefined($currency);
                $quantity = $currencyOrQuantity;
            } else {
                $currency = $currencyOrQuantity;
                $quantity = 1;
            }
        }


        $method = 'get' . ucfirst($field);


        if ($price != null && method_exists($price, $method)) {
            return $this->service->getFormattedPrice($price->getCurrency(), $quantity * $price->$method());
        }

        return '';
    }

    public function getGlobals() {
        return array(
        );
    }

    public function getFunctions() {

        return array(
            'canAddToCart' => new \Twig_Function_Method($this, 'canAddToCart', array('is_safe' => array('html'))),
        );
    }

    public function canAddToCart($price, $currency = null) {

        if ($currency == null) {
            $currency = $this->container->get('shop_core.currency')->getCurrency();
        }


        if ($price != null) {
            foreach ($this->listFunctionGetPrices as $value) {
                if ($price->$value["function"]() > 0) {
                    return true;
                }
            }
            if ($price->getFee() > 0) {
                return true;
            }
        }

        return false;
    }

    public function getFirstPrice($price, $currency = null) {
        if ($currency == null) {
            $currency = $this->container->get('shop_core.currency')->getCurrency();
        }
        $currency = $this->container->get('shop_core.currency')->getCurrency();

        $translator = $this->container->get('translator');

        $priceResponse = $translator->trans("not_available");



        if ($price != null) {
            foreach ($this->listFunctionGetPrices as $value) {
                if ($price->$value["function"]() > 0) {
                    $priceResponse = ucfirst($translator->trans("product.price_"))
                            . $this->service->getFormattedPrice($price->getCurrency(), $price->$value["function"]());
                    if ($value["suffixPrice"] != "") {
                        $priceResponse .= " / "
                                . $translator->trans($value["suffixPrice"]);
                    }
                    if ($price->getFee() > 0) {
                        $priceResponse .= "<br />"
                                . ucfirst($translator->trans("product.fees_"))
                                . $this->service->getFormattedPrice($price->getCurrency(), $price->getFee());
                    }
                    return $priceResponse;
                }
            }
        }

        return $priceResponse;
    }


    public function getName() {
        return 'product_extension';
    }

}
