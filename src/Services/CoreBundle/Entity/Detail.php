<?php

namespace Services\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetailName
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Detail {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Services\CoreBundle\Entity\DetailName", cascade={"persist"})
     */
    private $detailName;

    /**
     * @ORM\Column( type="string", length=255, nullable=true)
     */
    private $value;

    public function __toString() {
        return $this->value ;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Detail
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set detailName
     *
     * @param \Services\CoreBundle\Entity\DetailName $detailName
     * @return Detail
     */
    public function setDetailName(\Services\CoreBundle\Entity\DetailName $detailName = null)
    {
        $this->detailName = $detailName;

        return $this;
    }

    /**
     * Get detailName
     *
     * @return \Services\CoreBundle\Entity\DetailName 
     */
    public function getDetailName()
    {
        return $this->detailName;
    }
}
