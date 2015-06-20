<?php

namespace Sinenco\CoreBundle\Form;

use Sinenco\CoreBundle\Form\CustomFormType;
//use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sinenco\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shop\UserBundle\Form\UserAddressType;

class ProfileEditType extends CustomFormType implements ContainerAwareInterface {

    private $container;

    public function buildForm(FormBuilderInterface $builder, array $options) {

        // $this->setBuilder($builder) ;

        $options = array('disabled' => true);

        $this->editFormOptions('email', $options, $builder);
        $this->editFormOptions('first_name', $options, $builder);
        $this->editFormOptions('last_name', $options, $builder);
        $this->editFormOptions('plainPassword', array('required' => false), $builder);

        $this->moveAfter($builder, 'email', 'last_name');
        //$this->editFormOptions('email', array('position' => 'last'), $builder); IMPOSSIBLE


        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //$this->editFormOptions('plainPassword', array('position' => 'last'), $builder);
        } else {
            $builder
                    ->remove('plainPassword')
            ;
        }

        $currencyChoice = $this->container->get("shop_core.currency")->getChoiceCurrency();
        
        $builder
                ->add('phone', 'text', array('required' => false))
                ->add('currency', 'choice', array(
                    'choices' => $currencyChoice,
                    'required' => true))
                ->add('balance', 'text', array('disabled' => true))
                ->add('save', 'submit', [
                    'label' => 'root.save'
                ])
        ;
    }

    public function getName() {
        return 'sinenco_core_profile_edit';
    }

    public function getParent() {
        return new RegistrationFormType();
    }

    public function __construct(ContainerInterface $container) {
        $this->setContainer($container);
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

}
