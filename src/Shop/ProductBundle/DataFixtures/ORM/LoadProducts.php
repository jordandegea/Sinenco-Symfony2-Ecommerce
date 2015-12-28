<?php

// src/Shop/ProductBundle/DataFixtures/ORM/LoadProducts.php

namespace Shop\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Shop\ProductBundle\Entity\Product;
use Shop\ProductBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Shop\ProductBundle\Entity\Prices;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadProducts extends AbstractFixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {
        $this->loadCategories($manager);


        for ($i = 1; $i < 10; $i++) {
            $price = new Prices;
            $price->setOneTime($i);
            $price->setCurrency(
                    $manager->getRepository('ShopCoreBundle:Currencies')->findOneByCode("USD")
            );

            $this->setReference("price_" . $i, $price);



            $name = "Product " . $i;

            // On crée l'utilisateur
            $product = new Product;

            $product->setCanonicalName("product" . $i);
            $product->translate("fr")->setName("fr_" . "p" . $i);
            $product->translate("en")->setName("en_" . "p" . $i);
            $product->translate("fr")->setShortDescription("fr_" . "d" . $i);
            $product->translate("en")->setShortDescription("en_" . "d" . $i);
            $product->translate("fr")->setLongDescription("fr_" . "d" . $i);
            $product->translate("en")->setLongDescription("en_" . "d" . $i);
            $product->setCategory($this->getReference("c" . $i));

            //$product->addPrice($this->getReference("price_" . $i));

            // On le persiste
            $manager->persist($product);
            $manager->persist($price);
            $product->mergeNewTranslations();
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }

    public function loadCategories($manager) {

        for ($i = 0; $i < 10; $i++) {

            $name = "Catégorie " . $i;
            $canonicalName = "c" . $i;


            $category = new Category;
            $category->setCanonicalName("category" . $i);
            $category->translate("fr")->setName("fr_" . "w" . $i);
            $category->translate("en")->setName("en_" . "w" . $i);

            $this->setReference($canonicalName, $category);

            // On le persiste
            $manager->persist($category);
            $category->mergeNewTranslations();
            if ($i % 3 != 0) {
                $category->setParentCategory($this->getReference("c" . ($i - 1)));
            }
            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getDependencies() {
        // fixture classes that this fixture is dependent on
        return array('Shop\CoreBundle\DataFixtures\ORM\LoadCurrencies');
    }

}
