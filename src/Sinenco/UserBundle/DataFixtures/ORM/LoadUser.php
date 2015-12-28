<?php

// src/Sinenco/UserBundle/DataFixtures/ORM/LoadUser.php

namespace Sinenco\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sinenco\UserBundle\Entity\User;
use Shop\UserBundle\Entity\UserAddress;

class LoadUser implements FixtureInterface {

    public function load(ObjectManager $manager) {
        // Les noms d'utilisateurs à créer
        $listNames = array('Alexandre', 'Marine', 'Anna', 'Jordan');

        foreach ($listNames as $name) {

            // On crée l'utilisateur
            $user = new User;

            // Le nom d'utilisateur et le mot de passe sont identiques
            $user->setUsername($name);
            $user->setLastName($name);
            $user->setFirstName($name);
            $user->setPlainPassword($name);
            $user->setEnabled(true);
            $user->setEmail($name . "@sinenco.com");

            // On définit uniquement le role ROLE_USER qui est le role de base
            $user->setRoles(array('ROLE_ADMIN'));



            // On le persiste
            $manager->persist($user);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }

}
