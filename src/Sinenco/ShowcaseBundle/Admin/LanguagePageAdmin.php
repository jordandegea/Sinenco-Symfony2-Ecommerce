<?php

namespace Sinenco\ShowcaseBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LanguagePageAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->add('canonicalName')
                ->add('page')
                ->add('image', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->add('language')
                ->add('name')
                ->add('sections', 'sonata_type_collection', array(
                    'by_reference' => false
                        ), array(
                    'sortable' => 'id',
                ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('language')
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
