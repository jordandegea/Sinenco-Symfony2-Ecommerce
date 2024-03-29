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
                ->add('translations', 'a2lix_translations', array(
                    'fields' => array(
                        'name' => array(),
                        'short_description' => array(
                            'field_type' => 'textarea',
                            'attr' => array(
                                'class' => 'ckeditor'
                            )
                        ),
                        'long_description' => array(
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
                ->add('canonicalName')
                ->add(
                        'category', 'entity', array(
                    'class' => 'Shop\ProductBundle\Entity\Category'
                        )
                )
                ->add('image', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'products_image')
                ))
                ->add('file', 'sonata_type_model_list', array(), array(
                    'link_parameters' => array('context' => 'products_file')
                ))
                ->add('related', 'sonata_type_model', array(
                    'multiple' => true,
                    'by_reference' => false,
                    'required' => false
                        )
                )
                ->end()
                ->with('Options', array('tab' => false, 'class' => 'col-md-6'))
                ->add('options', 'sonata_type_model', array(
                    'multiple' => true,
                    'by_reference' => false,
                    'required' => false
                        )
                )
                ->end()
                ->end()
                ->with('Prices', array('tab' => false, 'class' => 'col-md-6'))
                ->add('price', 'sonata_type_admin', array(), array('edit' => 'inline'))
                ->setHelps(array(
                    'canonicalName' => 'pattern : /^[a-z0-9\-]+$/',
                ))
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
