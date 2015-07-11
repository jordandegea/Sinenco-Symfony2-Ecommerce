<?php

namespace Shop\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ReviewAdmin extends Admin {

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
                ->add('content', 'textarea', array(
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
                ->add('createdAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                    'format' => 'dd.MM.yyyy, HH:mm:ss'
                ))
                ->end()
                ->end()


        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('user')
                ->add('product')
                ->add('checked')

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('title')
                ->add('user')
                ->add('product')
                ->add('checked')
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
