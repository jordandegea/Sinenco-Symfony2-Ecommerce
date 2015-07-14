<?php

namespace Shop\CartBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CartItemAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('Amount', array('tab' => false, 'class' => 'col-md-4'))
                ->add('prices')
                ->add('product')
                ->add('purchase')
                ->end()->end()
                
                ->with('Amount', array('tab' => false, 'class' => 'col-md-4'))
                ->add('optionsValues')
                ->end()->end()
                ->with('Amount', array('tab' => false, 'class' => 'col-md-4'))
                ->add('hiddenValues')
                ->end()->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->add('_action', 'actions', [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ]
                        ]
                )
        //->add('names')
        ;
    }

}
