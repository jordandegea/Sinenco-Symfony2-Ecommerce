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
                ->with("Default", array('tab' => true))
                ->add('canonicalName')
                ->add('page')
                ->add('language')
                ->end()
                ->end()
                ->with("Textes", array('tab' => true))
                ->with('Content', array('tab' => false, 'class' => 'col-xs-12 col-md-6'))
                ->add('title')
                ->add('colorTextIntro')
                ->add('subtitle')
                ->add('colorTextBanner')
                ->end()
                ->with('Position', array('tab' => false, 'class' => 'col-xs-12 col-md-6'))
                ->add("align", 'choice', array('choices' => [
                        'center' => "Center",
                        'left' => "Left",
                        'right' => "Right",
                    ]
                ))
                ->add("paddings", null, array(
                    'help'=>"top% right% bottom% left%"
                ))
                ->end()
                ->end()
                ->with("Images", array('tab' => true))
                ->add('imageShowcase', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->add('imageIntro', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->add('imageBanner', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'showcase_image')
                ))
                ->end()
                ->end()
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
