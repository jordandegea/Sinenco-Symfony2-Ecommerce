<?php

namespace Sinenco\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Defaults',array('tab' => false, 'class' => 'col-md-6'))
                    ->add('id')
                    ->add('company')
                    ->add('firstName')
                    ->add('lastName')
                    ->add('balance')
                    ->add('currency')
                    ->add('phone')
                    ->add('sponsor')
                    ->end()
                ->end()
                ->with('Base User', array('tab' => false, 'class' => 'col-md-6'))
                    ->add('username')
                    ->add('usernameCanonical')
                    ->add('email')
                    ->add('emailCanonical')
                    ->add('enabled')
                    ->add('lastLogin', 'sonata_type_datetime_picker', array(
                        'dp_side_by_side' => true,
                        'dp_use_current' => false,
                        'dp_use_seconds' => false,
                        'format' => 'dd.MM.yyyy, HH:mm:ss'
                    ))
                    ->add('passwordRequestedAt', 'sonata_type_datetime_picker', array(
                        'dp_side_by_side' => true,
                        'dp_use_current' => false,
                        'dp_use_seconds' => false,
                        'format' => 'dd.MM.yyyy, HH:mm:ss'
                    ))
                    ->end()
                ->end()
                ->with('Rentings', array('tab' => true))
                    ->add('rentings', 'sonata_type_collection', array(
                            'by_reference' => false
                        ), array(
                                        'edit' => 'inline',
                                        'inline' => 'table',
                                        'sortable' => 'id',
                        ))
                    ->end()
                ->end()
                ->with('Addresses', array('tab' => true))
                    ->add('userAddress', 'sonata_type_collection', array(
                            'by_reference' => false
                        ), array(
                                        'edit' => 'inline',
                                        'inline' => 'table',
                                        'sortable' => 'id',
                        ))
                    ->end()
                ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('firstName')
                ->add('lastName')
                ->add('balance','doctrine_orm_number')
                ->add('phone')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->add('firstName')
                ->add('lastName')
                ->add('balance')
                ->add('phone')
                ->add('rentings')
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
