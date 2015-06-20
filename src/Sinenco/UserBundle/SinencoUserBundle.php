<?php

// src/Sinenco/UserBundle/OCUserBundle.php

namespace Sinenco\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SinencoUserBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
