<?php

namespace Services\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ServiceAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $container = $this->getConfigurationPool()->getContainer();
        $servicesAvailableParameters = $container->getParameter('core_service.services_available');
        $servicesAvailable= [] ;
        foreach ( $servicesAvailableParameters as $key => $value){
            $servicesAvailable[$key] = $key ;
        }
        $formMapper
                ->add('name', 'choice', array(
                    'choices' => $servicesAvailable
                ))
                ->add('translations', 'a2lix_translations')
                ->add('category', 'entity', array(
                    'class' => 'Shop\ProductBundle\Entity\Category',
                    'required' => true
                        )
                )
                ->add('product','entity', array(
                    'class' => 'ShopProductBundle:Product',
                    'required' => false
                ))
                ->add('detailsName')
                ->add('useIoncube', null, 
                        array(
                            'required' => false
                        ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('name')
                ->add('_action', 'actions', [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ]
                        ]
                )
        ;
    }

}
