<?php

namespace Shop\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Shop\ProductBundle\Entity\Product ; 
use Sinenco\UserBundle\Entity\User ; 
/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Review
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="date")
     */
    private $createdAt;

    
    /**
     *  @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", cascade={"persist"})
     *  @ORM\JoinTable()
     *  @ORM\JoinColumn(nullable=true)
     * */
    private $user;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Shop\ProductBundle\Entity\Product", inversedBy="reviews", cascade={"persist"})
     *  @ORM\JoinTable()
     *  @ORM\JoinColumn(nullable=false)
     * */
    private $product;
    
    /**
     * @var text
     *
     * @ORM\Column(type="string", length=100)
     */
    private $title;
    
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $grade;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text")
     */
    private $content;
    
    
    public function __toString() {
        $length = strlen($this->content)-1 ;
        if ( $length > 20 ){
            $length = 20 ; 
        }
        $cut = substr($this->content, 0, $length) ; 
        if ( $cut == "" ){
            return "new";
        }
        return $cut ; 
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Review
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Review
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     *
     * @return Review
     */
    public function setUser(\Sinenco\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param \Shop\ProductBundle\Entity\Product $product
     *
     * @return Review
     */
    public function setProduct(\Shop\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Shop\ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Review
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return Review
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     *
     * @return Review
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return integer
     */
    public function getGrade()
    {
        return $this->grade;
    }
}
