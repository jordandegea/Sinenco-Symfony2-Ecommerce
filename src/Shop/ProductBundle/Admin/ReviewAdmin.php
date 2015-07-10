<?php

namespace Shop\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Contents', array('tab' => false, 'class' => 'col-md-6'))
                ->add('title')
                ->add('grade', 'choice', array(
                    'choices' => array(
                        '0' => '0',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ),
                    'multiple' => false,
                ))
                ->add('content', null, array(
                    'field_type' => 'textarea',
                    'attr' => array(
                        'class' => 'ckeditor'
                    )
                ))
                ->end()
                ->end()
                ->with('Defaults', array('tab' => false, 'class' => 'col-md-6'))
                ->add('checked')
                ->add('product')
                ->add('user')
                ->add('createdAt')
                ->end()
                ->end()


        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('price')

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
