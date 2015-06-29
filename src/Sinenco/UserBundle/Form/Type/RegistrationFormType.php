<?php

// src/Sinenco/UserBundle/Form/Type/RegistrationFormType.php

namespace Sinenco\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder
                ->remove('username')
                ->add('first_name', 'text', array('required' => true))
                ->add('last_name', 'text', array('required' => true))
                ->add('company', 'text', array(
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'shop.userbundle.optional',
                    )
                ))
        ;
        foreach ($builder as $value) {
            $builder->add($value);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        parent::setDefaultOptions($resolver);
    }

    public function getParent() {
        return 'fos_user_registration';
    }

    public function getName() {
        return 'sinenco_user_registration';
    }

}
