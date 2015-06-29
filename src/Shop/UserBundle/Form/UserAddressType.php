<?php

namespace Shop\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sinenco\UserBundle\Form\Type\RegistrationFormType;

class UserAddressType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
               
                ->add('address', 'text', array(
                    "mapped" => false,
                    'attr' => array(
                        'placeholder' => 'shop.userbundle.useForAddress',
                    ),
                    'required' => true
                ))
                ->add('streetNumber', 'text', array('required' => false))
                ->add('route', 'text', array('required' => false))
                ->add('additionalAddress', 'text', array('required' => false))
                ->add('city', 'text', array('required' => false))
                ->add('stateRegion', 'text', array('required' => false))
                ->add('zipCode', 'text', array('required' => false))
                ->add('country', 'text', array('required' => false))
                ->add('save', 'submit', [
                    'label' => 'root.save'
                ])
        ;
    }

    public function getName() {
        return 'shop_user_address_edit';
    }

}
