<?php

namespace Sinenco\UserBundle\Security;

class EmailUserProvider extends UserProvider {

    protected function findUser($username) {
        return $this->userManager->findUserByUsernameOrEmail($username);
    }

}
