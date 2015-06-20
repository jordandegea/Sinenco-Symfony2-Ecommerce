<?php

namespace Services\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ExclusionPolicy("all") 
 */
class Renting {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Services\CoreBundle\Entity\Service", inversedBy="rentings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User", inversedBy="rentings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \Date
     *
     * @ORM\Column(name="expiration", type="date")
     * @Expose
     */
    private $expiration;

    /**
     * @var \text
     *
     * @ORM\Column(name="license", type="text")
     * @Expose
     */
    private $license ;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Services\CoreBundle\Entity\Detail", cascade={"persist"})
     * @Expose
     */
    private $details;

    /**
     * Get the formatted name to display (NAME Firstname or username)
     * 
     * @param $separator: the separator between name and firstname (default: ' ')
     * @return String
     * @VirtualProperty 
     */
    public function getJson(){
        $details = array() ; 
        foreach ($this->details as $detail ){
            $details[$detail->getDetailName()->getCanonicalName()] = $detail->getValue() ;
        }
        
        return ["id" =>$this->id,
            "expiration" => $this->expiration->getTimestamp(),
            "details" => $details];
    } 
    
    public function __toString() {
        if ( $this->service != null ){
            return $this->service->getName();
        }else{
            return $this->id ;
        }
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
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return BaseService
     */
    public function setExpiration($expiration) {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime 
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist() {
        $this->license = "" ; 
        if ($this->expiration == null) {
            $this->expiration = new \DateTime();
        }
    }

    /**
     * Set service
     *
     * @param \Services\CoreBundle\Entity\Service $service
     * @return Renting
     */
    public function setService(\Services\CoreBundle\Entity\Service $service) {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Services\CoreBundle\Entity\Service 
     */
    public function getService() {
        return $this->service;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expiration = new \DateTime();
    }

    /**
     * Add details
     *
     * @param \Services\CoreBundle\Entity\Detail $details
     * @return Renting
     */
    public function addDetail(\Services\CoreBundle\Entity\Detail $details)
    {
        $this->details[] = $details;

        return $this;
    }

    /**
     * Remove details
     *
     * @param \Services\CoreBundle\Entity\Detail $details
     */
    public function removeDetail(\Services\CoreBundle\Entity\Detail $details)
    {
        $this->details->removeElement($details);
    }

    /**
     * Get details
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set user
     *
     * @param \Sinenco\UserBundle\Entity\User $user
     * @return Renting
     */
    public function setUser(\Sinenco\UserBundle\Entity\User $user)
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
     * Set license
     *
     * @param string $license
     *
     * @return Renting
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }
}
