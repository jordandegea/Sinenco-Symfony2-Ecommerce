<?php

namespace Sinenco\ProjectBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sinenco\ProjectBundle\Entity\Project;

class ProjectAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Defaults', array('tab' => false, 'class' => 'col-md-6'))
                ->add('user')
                ->add('createdAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => false,
                    'format' => 'dd.MM.yyyy, HH:mm:ss'
                ))
                ->add('state', 'choice', array('choices' => array(
                        Project::STATE_WAITING_DEV => 'Attente du dev',
                        Project::STATE_WAITING_USER => 'Attente du client',
                        Project::STATE_ACTIVE => 'Active',
                        Project::STATE_REFUSED => 'Refused'
            )))
                ->add('reference')
                ->end()
                ->end()
                ->with('Contents', array('tab' => false, 'class' => 'col-md-6'))
                ->add('title')
                ->add('summary', 'textarea', array(
                    'attr' => array(
                        'class' => "ckeditor"
                    )
                ))
                ->add('priceMin')
                ->add('priceMax')
                ->add('currency')
                ->end()
                ->end()
                ->with('Contents', array('tab' => false, 'class' => 'col-md-6'))
                ->add('chatLines')
                ->end()
                ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('title')
                ->add('reference')

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('id', 'url', array(
                    'label' => 'link',
                    'route' => array(
                        'name' => 'sinenco_project_detail',
                        'absolute' => true,
                        'parameters' => array('id' => 'id'),
                        'identifier_parameter_name' => 'id')))
                ->addIdentifier('title')
                ->add('priceMax')
                ->add('state', 'choice', array('choices' => array(
                        Project::STATE_WAITING_DEV => 'Attente du dev',
                        Project::STATE_WAITING_USER => 'Attente du client',
                        Project::STATE_ACTIVE => 'Active',
                        Project::STATE_REFUSED => 'Refused'
            )))
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
