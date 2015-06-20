<?php

namespace Shop\CartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CartItemPricesType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('oneTime')
                ->add('monthly')
                ->add('quarterly')
                ->add('semiannually')
                ->add('annually')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Shop\CartBundle\Entity\CartItemPrices'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'shop_cartbundle_cartitemprices';
    }

}
