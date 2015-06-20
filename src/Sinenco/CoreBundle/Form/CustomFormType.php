<?php

namespace Sinenco\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomFormType extends AbstractType {
    /*
      private $builder ;

      protected function setBuilder(FormBuilderInterface $builder){
      $this->$builder = $builder ;
      }
     */

    protected function editFormOptions($name, $optionsToAdd, $builder) {
        $field = $builder->get($name);         // get the field

        $options = $field->getOptions();            // get the options
        foreach ($optionsToAdd as $key => $value) {
            $options[$key] = $value;
        }
        $type = $field->getType()->getName();       // get the name of the type
        $builder->add($name, $type, $options); // replace the field
    }

    protected function moveAfter($builder, $field, $after) {
        if (!$builder->has($after)) {
            $builder->add($field);
            return;
        }

        if ($builder->has($field)) {
            $objet = $builder->get($field);
            $builder->remove($field);
        }

        $childs = $builder->all(); //return all child
        foreach ($childs as $child) {
            $builder->remove($child->getName());
        }

        foreach ($childs as $child) {
            $builder->add($child);
            if ($child->getName() == $after) {
                $builder->add((isset($objet)) ? $objet : $field );
            }
        }
    }

    protected function moveBefore($builder, $field, $before) {
        if (!$builder->has($before)) {
            $builder->add($field);
            return;
        }

        if ($builder->has($field)) {
            $objet = $builder->get($field);
            $builder->remove($field);
        }

        $childs = $builder->all(); //return all child
        foreach ($childs as $child) {
            $builder->remove($child->getName());
        }

        foreach ($childs as $child) {
            $builder->add($child);
            if ($child->getName() == $before) {
                $builder->add((isset($objet)) ? $objet : $field );
            }
        }
    }

    public function getName() {
        return "sinenco_core_custom_form_type";
    }

}
