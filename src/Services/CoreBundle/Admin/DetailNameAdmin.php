<?php

namespace Services\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DetailNameAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with("General",
                    array('class'       => 'col-md-12'))
                    ->add('canonicalName')
                    ->add('isEditable', null, array(
                        'required' => false
                    ))
                    ->add('isDisplayedOnList', null, array(
                        'required' => false
                    ))
                    ->add('attribute', null, array(
                        'required' => false
                    ))
                    ->add('translations', 'a2lix_translations')
                ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('isEditable')
                ->add('isDisplayedOnList')
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
