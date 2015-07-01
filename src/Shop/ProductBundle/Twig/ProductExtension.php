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
    private $em;
    private $listFunctionGetPrices = [
        ["function" => "getOneTime", "suffixPrice" => ""],
        ["function" => "getMonthly", "suffixPrice" => "calendar.month"],
        ["function" => "getQuarterly", "suffixPrice" => "calendar.quarter"],
        ["function" => "getSemiannually", "suffixPrice" => "calendar.semiannual"],
        ["function" => "getAnnually", "suffixPrice" => "calendar.year"]
    ];

    public function __construct($entityManager, $container) {
        $this->em = $entityManager;
        $this->container = $container;
        $this->service = $container->get("shop_products");
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('getFirstPrice', array($this, 'getFirstPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('getFieldPrice', array($this, 'getFieldPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('FormatPrice', array($this, 'FormatPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('ConvertPrice', array($this, 'ConvertPrice'), array('is_safe' => array('html'))),
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

    public function ConvertPrice($price, $from, $to = null) {

        $to = $this->service->getCurrencyIfNotDefined($to);

        return $this->container
                        ->get('shop_core.currency')
                        ->convertPrice(
                                $price, $from, $to);
    }

    public function FormatPrice($price, $currency = null) {

        $currency = $this->service->getCurrencyIfNotDefined($currency);

        if (is_string($currency)) {
            $currencyObject = $this->em
                    ->getRepository('ShopCoreBundle:Currencies')
                    ->findOneByCode($currency);
        } else {
            $currencyObject = $currency;
        }
        if ($currencyObject == null) {
            $currencyObjects = $this->em
                    ->getRepository('ShopCoreBundle:Currencies')
                    ->findAll();
            foreach ($currencyObjects as $object) {
                $currencyObject = $object;
                break;
            }
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

        if (is_string($currency)) {
            $currency = $this
                    ->em
                    ->getRepository('ShopCoreBundle:Currencies')
                    ->findOneByCode($currency);
        }

        $method = 'get' . ucfirst($field);
        $priceValue = $price->$method();

        if ($currency != $price->getCurrency()) {
            $priceValue = $this->convertPrice(
                    $priceValue, $price->getCurrency()->getCode(), $currency->getCode());
        }


        if ($price != null && method_exists($price, $method)) {
            return $this->service->getFormattedPrice($currency, $quantity * $priceValue);
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
            $currency = $this->container->get('shop_core.currency')->getCurrencyObject();
        } else {
            $currency = $this->container->get('shop_core.currency')->getAssociatedCurrency($currency);
        }
        $translator = $this->container->get('translator');

        $priceResponse = $translator->trans("not_available");

        if ($price != null) {
            foreach ($this->listFunctionGetPrices as $value) {
                if ($price->$value["function"]() > 0) {

                    if ($currency != $price->getCurrency()) {
                        $priceValue = $this->convertPrice(
                                $price->$value["function"](), $price->getCurrency()->getCode(), $currency->getCode());
                    } else {
                        $priceValue = $price->$value["function"]();
                    }

                    $priceResponse = ucfirst($translator->trans("product.price_"))
                            . $this->service->getFormattedPrice($currency, $priceValue);
                    if ($value["suffixPrice"] != "") {
                        $priceResponse .= " / "
                                . $translator->trans($value["suffixPrice"]);
                    }
                    if ($price->getFee() > 0) {
                        if ($currency != $price->getCurrency()) {
                            $priceValue = $this->convertPrice(
                                    $price->getFee(), $price->getCurrency()->getCode(), $currency->getCode());
                        } else {
                            $priceValue = $price->getFee();
                        }
                        $priceResponse .= "<br />"
                                . ucfirst($translator->trans("product.fees_"))
                                . $this->service->getFormattedPrice($currency, $priceValue);
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
