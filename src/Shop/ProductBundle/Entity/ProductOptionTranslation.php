<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class ProductOptionTranslation {

    use ORMBehaviors\Translatable\Translation;

    /**
     * @var array
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fieldName;

    /**
     * @var array
     *
     * @ORM\Column(type="string", length=511, nullable=true )
     */
    private $helps;

    /**
     * Set fieldName
     *
     * @param string $fieldName
     * @return AttributeTranslation
     */
    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string 
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * Set helps
     *
     * @param string $helps
     * @return AttributeTranslation
     */
    public function setHelps($helps) {
        $this->helps = $helps;

        return $this;
    }

    /**
     * Get helps
     *
     * @return string 
     */
    public function getHelps() {
        return $this->helps;
    }

}
