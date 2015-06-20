<?php

// src/Shop/CoreBundle/Services/Currency.php

namespace Sinenco\CoreBundle\Services;

use Doctrine\ORM\EntityManager,
    Symfony\Component\DependencyInjection\Container;

class MailerService {

    public function sendMail($controller, $subject, $address, $plain, $params = null, $renderPlain = false, $html = null,  $renderHTML = false ) {
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('webmaster@sinenco.com')
                ->setTo($address)
        ;

        if ($renderPlain) {
            $message->setBody(
                    $controller->renderView($plain, $params), 
                    'text/plain'
            );
        }else{
            $message->setBody($plain, 'text/plain');
        }
 

        if ( $html != null ){
            if ($renderHTML) {
                $message->setBody(
                        $controller->renderView($html, $params), 
                        'text/html'
                );
            } else {
                $message->setBody(
                        $controller->renderView($html, $params), 
                        'text/html');
            }
        }
        $controller->get('mailer')->send($message);
    }

}
