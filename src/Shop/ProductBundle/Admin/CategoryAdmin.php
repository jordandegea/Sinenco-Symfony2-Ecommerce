<?php

namespace Shop\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('Defaults', array('tab' => true))
                ->add('canonicalName')
                ->add('parentCategory')
                ->setHelps(array(
                    'canonicalName' => 'pattern : /^[a-z0-9\-]+$/',
                ))
                ->end()
                ->end()
                ->with('Languages', array('tab' => true))
                ->add('translations', 'a2lix_translations')
                ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('parentCategory')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('id')
                ->addIdentifier('canonicalName')
                ->add('parentCategory')
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
