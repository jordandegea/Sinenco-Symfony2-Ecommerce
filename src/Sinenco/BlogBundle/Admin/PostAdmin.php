<?php

namespace Sinenco\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Contents', array('tab' => false, 'class' => 'col-md-6'))
                ->add('translations', 'a2lix_translations', array(
                    'fields' => array(
                        'title' => array(),
                        'content' => array(
                            'field_type' => 'textarea',
                            'attr' => array(
                                'class' => 'ckeditor'
                            )
                        )
                    )
                        )
                )
                ->end()
                ->end()
                ->with('Defaults', array('tab' => false, 'class' => 'col-md-6'))
                ->add('enabled')
                ->add('canonicalName')
                ->add('user')
                ->add('createdAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                    'format' => 'dd.MM.yyyy, HH:mm:ss'
                ))
                ->add('closeCommentsAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                    'format' => 'dd.MM.yyyy, HH:mm:ss'
                ))
                ->add('image', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'products_image')
                ))
                ->end()
                ->end()
                ->with('Comments', array('tab' => true))
                ->add('comments', 'sonata_type_collection', array(
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
