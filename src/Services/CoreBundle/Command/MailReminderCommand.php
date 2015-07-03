<?php

namespace Services\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailReminderCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('services:reminder')
                ->setDescription('Send mail to clients with expiring services')
                /* ->addArgument(
                  'arguent',
                  InputArgument::OPTIONAL,
                  'Do we send'
                  ) */
                ->addOption(
                        'send', null, InputOption::VALUE_NONE, 'If set, the task will send mail'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        /* $name = $input->getArgument('name');
          if ($name) {
          $text = 'Hello '.$name;
          } else {
          $text = 'Hello';
          } */

        if ($input->getOption('send')) {
            $send = true;
        } else {
            $send = false;
        }

        $em = $this
                ->getContainer()
                ->get('doctrine')
                ->getManager();

        $repository = $em
                ->getRepository('SinencoUserBundle:User');
        
        $users = $repository->findAll();

        $i = 0;

        foreach ($users as $user) {
            if (true == $this->checkRentings($em, $user, $send)) {
                $i++;
            }
        }
        $output->write($i . " mails ");
        if ($send == true) {
            $output->writeln("sent");
        } else {
            $output->write(" to send ");
        }
    }

    private function checkRentings($em, $user, $send) {
        $RentingsRepository = $em
                ->getRepository('ServicesCoreBundle:Renting');

        $rentings = $RentingsRepository->findBy(array("user" => $user));

        $result = false;

        foreach ($rentings as $renting) {
            $expireTime = $renting->getExpiration()->diff(new \Datetime());
            if ($expireTime->invert == 0) {
                // Alors a location est expiré
            } else {
                echo $expireTime->days." ";
                switch ($expireTime->days) {
                    case 3:
                    case 7:
                    case 15:
                    case 30:
                    case 60:
                        $result = true;
                        break;
                    default: break;
                }
                if ($result == true) {
                    break;
                }
            }
        }

        if ($result == true) {
            // Alors on envoie le mail
            $this
                    ->getContainer()
                    ->get('mail_service')
                    ->sendMail(
                            $this->getContainer(), $this
                            ->getContainer()->get('translator')
                            ->trans('services.mail.subject'), $user
                            ->getEmail(), "ServicesCoreBundle:Mails:reminder.text.twig", array(
                        'user' => $user,
                        'rentings' => $rentings
                            ), true, "ServicesCoreBundle:Mails:reminder.html.twig", true
            );
            return true;
        }

        return false;
    }

}
