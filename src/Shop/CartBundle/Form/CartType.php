<?php

namespace Shop\CartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Shop\CartBundle\Form\CartProductType;

class CartType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('products', 'collection', array(
                    'type' => new CartItemType(),
                    'allow_add' => false,
                    'allow_delete' => false))
                ->add('update', 'submit')
                ->add('next_step', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Shop\CartBundle\Entity\Cart'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'shop_cartbundle_cart';
    }

}
