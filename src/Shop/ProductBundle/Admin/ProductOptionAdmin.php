<?php

namespace Shop\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductOptionAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with("Default")
                ->add('canonicalName')
                ->add('required', null, array('required' => false))
                
                ->add('isEditable', null, array(
                    'required' => false
                ))
                ->add('translations', 'a2lix_translations', array(
                    'fields' => array(
                        'fieldName' => array(),
                        'helps' => array(
                            'required' => false
                        )
                    )
                ))
                ->end()
                ->with("Options")
                ->add('type', 'choice', array('choices' => array(
                        'textfield' => 'TextField',
                        'checkbox' => 'CheckBox',
                        'choice' => 'Choice'
                    ))
                )
                ->add('values', null, array(
                    'by_reference' => false
                        )
                )
                ->end()
                ->setHelps(array(
                    'canonicalName' => 'Should be reprensentative of following fields',
                    'translations' => 'For a choice, fieldValues should writed like : option1;option2;option3',
                ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
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
