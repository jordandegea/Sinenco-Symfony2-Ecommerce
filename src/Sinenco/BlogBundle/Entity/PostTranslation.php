<?php

namespace Sinenco\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class PostTranslation {

    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $content;

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param  string
     * @return null
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param  string
     * @return null
     */
    public function setContent($content) {
        $this->content = $content;
    }

}
