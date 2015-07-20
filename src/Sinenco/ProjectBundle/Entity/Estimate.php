<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estimate
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Estimate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

