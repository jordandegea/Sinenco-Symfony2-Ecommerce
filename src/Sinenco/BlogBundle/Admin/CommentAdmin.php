<?php

namespace Sinenco\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Contents', array('tab' => false, 'class' => 'col-md-6'))
                ->add('post')
                ->add('content')
                ->end()
                ->end()
                ->with('Defaults', array('tab' => false, 'class' => 'col-md-6'))
                ->add('user')
                ->add('createdAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                ))
                ->end()
                ->end()
                ->with('Comments', array('tab' => true, 'class' => 'col-md-6'))
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
                ->add('id')
                ->add('content')

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->add('content')
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
