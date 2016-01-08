<?php

namespace Sinenco\ShowcaseBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TabAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->add('canonicalName')
                ->add('languagePage')
                ->add('btnColor', 'choice', array('choices' => [
                        'default' => "Default",
                        'primary' => "Primary",
                        'success' => "Success",
                        'info' => "Info",
                        'warning' => "Warning",
                        'danger' => "Danger"
            ]))
                ->add('name')
                ->add('sections', 'sonata_type_collection', ['label' => 'Section',
                    'required' => false, 'cascade_validation' => true,
                    'by_reference' => false], ['edit' => 'inline', 'inline' => 'table'])
                ->end()


        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->addIdentifier('canonicalName')
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
