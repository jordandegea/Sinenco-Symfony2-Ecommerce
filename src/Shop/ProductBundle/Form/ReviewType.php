<?php

namespace Shop\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('title', 'text')
                ->add('grade', 'choice', array(
                    'choices' => array(
                        '10' => '10',
                        '9' => '9',
                        '8' => '8',
                        '7' => '7',
                        '6' => '6',
                        '5' => '5',
                        '4' => '4',
                        '3' => '3',
                        '2' => '2',
                        '1' => '1',
                        '0' => '0',
                    ),
                    'multiple' => false,
                ))
                ->add('content', 'textarea')
                ->add('submit', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Shop\ProductBundle\Entity\Review'
        ));
    }

    public function getName() {

        return 'shop_productbundle_reviews';
    }

}
