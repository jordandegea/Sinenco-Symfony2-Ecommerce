<?php

namespace Sinenco\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Document {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;
    
    /**
     * @ORM\OneToMany(targetEntity="Sinenco\ProjectBundle\Entity\Chapter", mappedBy="document", cascade={"remove", "persist"})
     */
    protected $parts;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

}
