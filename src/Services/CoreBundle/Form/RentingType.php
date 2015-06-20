<?php


namespace Services\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RentingType extends AbstractType

{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('expiration', 'date', array(
          'read_only' => true 
      ))
      ->add('details', 'collection', array(
                    'type' => new DetailType(),
                    'allow_add' => false,
                    'allow_delete' => false))
      ->add('save',      'submit')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Services\CoreBundle\Entity\Renting'
    ));
  }

  public function getName()
  {
    return 'services_corebundle_renting';
  }

}