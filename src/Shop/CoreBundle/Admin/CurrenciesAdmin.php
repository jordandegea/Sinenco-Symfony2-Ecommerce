<?php

namespace Shop\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CurrenciesAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('code')
                ->add('suffix')
                ->add('prefix')
                ->add('format')
                ->add('rate')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('code')
                ->add('suffix')
                ->add('prefix')
                ->add('format')
                ->add('rate')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('id')
                ->add('code')
                ->add('suffix')
                ->add('prefix')
                ->add('format')
                ->add('rate')
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
