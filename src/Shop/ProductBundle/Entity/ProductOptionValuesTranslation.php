<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class ProductOptionValuesTranslation {

    use ORMBehaviors\Translatable\Translation;

    /**
     * @var array
     *
     * @ORM\Column(type="string", length=511, nullable=true )
     * If this is an array
     */
    private $value;

    /**
     * Set value
     *
     * @param string $value
     * @return ProductOptionValuesTranslation
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

}
