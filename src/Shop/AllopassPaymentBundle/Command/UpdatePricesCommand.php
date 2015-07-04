<?php

namespace Shop\AllopassPaymentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Shop\AllopassPaymentBundle\API\api\AllopassAPI;

class UpdatePricesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('allopass:payment:update')
                ->setDescription('Update price point for allopass')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $ids = $this->getContainer()->getParameter("allopass.ids");
        $idd = $this->getContainer()->getParameter("allopass.idd");

        $api = new AllopassAPI();

        $response = $api->getOnetimePricing(array('site_id' => $ids));
        $fp = fopen("test.txt", "w+");
        foreach ($response->getMarkets() as $market) {
            var_dump($market, $fp);
        }
        fclose($fp);

        $output->write("");
    }

}
