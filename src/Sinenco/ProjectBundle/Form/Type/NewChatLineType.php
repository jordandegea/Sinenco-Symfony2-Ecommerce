<?php

// src/Sinenco/UserBundle/Form/Type/RegistrationFormType.php

namespace Sinenco\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewChatLineType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('content', 'textarea', array(
                    'attr' => array(
                        'class' => 'ckeditor editing',
                        'rows' => 4
                    ))
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Sinenco\ProjectBundle\Entity\ChatLine'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'sinenco_project_cahtline_new';
    }

}
