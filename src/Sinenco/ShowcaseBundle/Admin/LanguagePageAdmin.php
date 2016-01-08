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
                ->add('imageIntro', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->add('imageBanner', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->add('language')
                ->add('title')
                ->add('colorTextIntro')
                ->add('subtitle')
                ->add('colorTextBanner')
                
                ->add('tabs', null)
                
                
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('canonicalName')
                ->add('language')
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
