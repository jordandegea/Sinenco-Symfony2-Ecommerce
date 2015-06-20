<?php

namespace Shop\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PriceAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('currency')
                ->add('one_time')
                ->add('fee')
                ->add('monthly')
                ->add('quarterly')
                ->add('semiannually')
                ->add('annually')

        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('currency')
                ->add('one_time')
                ->add('fee')
                ->add('monthly')
                ->add('quarterly')
                ->add('semiannually')
                ->add('annually')

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('currency')
                ->addIdentifier('one_time')
                ->addIdentifier('fee')
                ->addIdentifier('monthly')
                ->add('quarterly')
                ->add('semiannually')
                ->add('annually')
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
