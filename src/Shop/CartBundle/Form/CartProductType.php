<?php

// form vide mais a garder pour acceder au valeur de l'entité

namespace Shop\CartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Shop\CartBundle\Form\CartProductAttributeType;

class CartProductType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Shop\ProductBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'shop_cartbundle_cartproduct';
    }

}
