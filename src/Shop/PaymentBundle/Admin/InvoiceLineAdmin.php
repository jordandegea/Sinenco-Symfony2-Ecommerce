<?php

namespace Shop\PaymentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class InvoiceLineAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('name')
                ->add('unitPrice')
                ->add('quantity');
        //if (!$formMapper->getFormBuilder()->getForm()->getParent() == null ) {
            $formMapper
                    ->add('options', 'sonata_type_collection', array(
                        'by_reference' => false
                            ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'id',
                    ))
            ;
        //}
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
