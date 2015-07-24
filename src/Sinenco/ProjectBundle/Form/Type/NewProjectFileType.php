<?php

// src/Sinenco/UserBundle/Form/Type/RegistrationFormType.php

namespace Sinenco\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewProjectFileType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('file', 'sonata_media_type', array(
                    'provider' => 'sonata.media.provider.file',
                    'context' => 'project_file'
                ))
        ;
        $builder->get('file')->add('unlink', 'hidden', ['mapped' => false, 'data' => false]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Sinenco\ProjectBundle\Entity\ProjectFile'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'sinenco_project_new';
    }

}
