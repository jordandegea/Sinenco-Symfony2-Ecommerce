<?php

namespace Sinenco\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Sonata\MediaBundle\Entity\Media;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Post {

    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="canonical_name", type="string", length=255)
     * @Assert\Regex("/^[a-z0-9\-]+$/")
     * @Assert\Length(min=2)
     */
    private $canonicalName;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closeCommentsAt", type="datetime")
     */
    private $closeCommentsAt;

    /**
     *  @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", cascade={"persist"})
     *  @ORM\JoinTable()
     * */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Sinenco\BlogBundle\Entity\Comment", mappedBy="post", cascade={"persist"})
     * */
    private $comments;

    public function __toString() {
        return $this->canonicalName;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     * @return Category
     */
    public function setCanonicalName($canonicalName) {
        $this->canonicalName = $canonicalName;

        return $this;
    }

    /**
     * Get canonicalName
     *
     * @return string 
     */
    public function getCanonicalName() {
        return $this->canonicalName;
    }

    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return Product
     */
    public function setImage(Media $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set closeCommentsAt
     *
     * @param \DateTime $closeCommentsAt
     *
     * @return Post
     */
    public function setCloseCommentsAt($closeCommentsAt) {
        $this->closeCommentsAt = $closeCommentsAt;

        return $this;
    }

    /**
     * Get closeCommentsAt
     *
     * @return \DateTime
     */
    public function getCloseCommentsAt() {
        return $this->closeCommentsAt;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     *
     * @return Post
     */
    public function setUser(\Sinenco\UserBundle\Entity\User $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add comment
     *
     * @param \Sinenco\BlogBundle\Entity\Comment $comment
     *
     * @return Post
     */
    public function addComment(\Sinenco\BlogBundle\Entity\Comment $comment) {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Sinenco\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\Sinenco\BlogBundle\Entity\Comment $comment) {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }

}
