<?php

namespace Sinenco\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\Container;

class UserMailer implements MailerInterface {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Send an email to a user to confirm the account creation
     *
     * @param UserInterface $user
     */
    public function sendConfirmationEmailMessage(UserInterface $user) {
        
        $url = $this->container->get('router')->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        
        $params = [
            "user" => $user,
            "confirmationUrl" => $url
        ];
        $this->container
                ->get("mail_service")
                ->sendMail($this->container, "Sinenco : Welcome", $user->getEmail(), "SinencoUserBundle:Mail:confirm.text.twig", $params, true, "SinencoUserBundle:Mail:confirm.html.twig", true);
    }

    /**
     * Send an email to a user to confirm the password reset
     *
     * @param UserInterface $user
     */
    function sendResettingEmailMessage(UserInterface $user) {
        
        $url = $this->container->get('router')->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        
        $params = [
            "firstName" => $user,
            "confirmationUrl" => $url
        ];
        $this->container
                ->get("mail_service")
                ->sendMail($this->container, "Sinenco : Welcome", $user->getEmail(), "SinencoUserBundle:Mail:resetting.text.twig", $params, true, "SinencoUserBundle:Mail:resetting.html.twig", true);
    }

}
