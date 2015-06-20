<?php

namespace Services\CoreBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sinenco\CoreBundle\Form\CustomFormType ; 

class DetailType extends CustomFormType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('value')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Services\CoreBundle\Entity\Detail'
    ));
  }

  public function getName()
  {
    return 'services_corebundle_detail';
  }

}