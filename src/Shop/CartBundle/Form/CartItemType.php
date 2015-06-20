<?php

namespace Shop\CartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CartItemType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('optionsValues')
                //->add('cart')v
                ->add('product', new CartProductType())
                ->add('prices', new CartItemPricesType())
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Shop\CartBundle\Entity\CartItem'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'shop_cartbundle_cartitem';
    }

}
