<?php

// src/Shop/CoreBundle/DataFixtures/ORM/LoadCurrencies.php

namespace Shop\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Shop\CoreBundle\Entity\Currencies;

class LoadCurrencies extends AbstractFixture {

    public function load(ObjectManager $manager) {

        $currency = new Currencies;
        $currency->setCode("USD");
        $currency->setFormat(1);
        $currency->setPrefix('$');
        $currency->setRate(1.000);
        $currency->setSuffix("USD");

        $manager->persist($currency);
        $this->setReference("currency_USD", $currency);

        $currency = new Currencies;
        $currency->setCode("EUR");
        $currency->setFormat(1);
        $currency->setPrefix('€');
        $currency->setRate(0.8839);
        $currency->setSuffix("EUR");

        $manager->persist($currency);
        $this->setReference("currency_EUR", $currency);


        // On déclenche l'enregistrement
        $manager->flush();
    }

}
