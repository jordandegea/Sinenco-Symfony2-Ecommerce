<?php

namespace Shop\PaymentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class InvoiceAdmin extends Admin {

    protected $formOptions = array(
        'cascade_validation' => true
    );

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('Defaults', array('tab' => true))
                ->with('Amount', array('tab' => false, 'class' => 'col-md-4'))
                ->add('credit')
                ->add('totalPrice', null, array(
                    'readonly' => true
                ))
                ->add('totalPriceEUR', null, array(
                    'readonly' => true
                ))
                ->end()
                ->with('Base', array('tab' => false, 'class' => 'col-md-4'))
                ->add('number')
                ->add('date', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                    'format' => 'dd.MM.yyyy, HH:mm:ss'
                ))
                ->add('user')
                ->add('cart')
                ->add('currency')
                ->end()
                ->with('Addresses', array('tab' => false, 'class' => 'col-md-4'))
                ->add('addressSender', 'textarea', array(
                    'attr' => array(
                        'rows' => 5
                    )
                ))
                ->add('addressReceiver', 'textarea', array(
                    'attr' => array(
                        'rows' => 5
                    )
                ))
                ->add('credit')
                ->add('totalPrice')
                ->add('totalPriceEUR')
                ->end()
                ->end()
                ->with('Transactions', array('tab' => true))
                ->add('transactions')
                ->end()
                ->end()
                ->with('Products', array('tab' => true))
                ->add('lines', 'sonata_type_collection', array(
                    'by_reference' => false,
                    'required' => false
                        ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id',
            'expanded' => true
                ))
                ->end()
                ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('number')
                ->add('date')
                ->add('user')
        ;
    }
    
    public function getName()
    {
        return 'shop_payment_admin_invoice';
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('number')
                ->add('date')
                ->add('user')
                ->add('totalPrice')
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
