<?php

namespace Services\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateLicenseCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('services:license:create')
                ->setDescription('Create license for a renting in parameters')
                ->addArgument(
                        'id', InputArgument::REQUIRED, 'Renting Id'
                )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $id = $input->getArgument('id') ;

        $em = $this
                ->getContainer()
                ->get('doctrine')
                ->getManager();

        $repository = $em
                ->getRepository('ServicesCoreBundle:Renting');

        $renting = $repository->find((integer) $id);
        
        if ( $renting == null ) {
            echo "No renting" ;
        }else{
            $license = $this->getContainer()->get('services_core.core_services')->createLicense($renting) ; 
            $renting->setLicense($license);
            echo $license ; 
            
        }

        $output->writeln("");
    }


}
