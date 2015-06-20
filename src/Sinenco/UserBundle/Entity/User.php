<?php

namespace Sinenco\UserBundle\Entity;

use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table("user")
 * @ORM\Entity(repositoryClass="Sinenco\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class User extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Le Parrain
     * @ORM\ManyToOne(targetEntity="Sinenco\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sponsor; 

    /**
     * @var decimal
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $balance;

    /**
     * @ORM\Column(name="currency", type="string", length=10)
     * @Assert\Length(min=2)
     */
    private $currency ; 
    
    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $lastName;

    /**
     * Attention, ici c'est un OneToMany associé à un ManyToMany pour eviter d'avoir 
     * une relation bidirectionnelle.
     * 
     * @ORM\ManyToMany(targetEntity="Shop\UserBundle\Entity\UserAddress", cascade={"persist"})
     * @ORM\JoinTable(name="users_useraddresses",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="useraddress_id", referencedColumnName="id", unique=true)}
     *      )
     * */
    private $userAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     * @Assert\Regex(pattern="/(\+)?\d+/")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="Services\CoreBundle\Entity\Renting", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    private $rentings;

    /**
     * @param decimal(10,2) $balance
     * @return User
     */
    public function setBalance($balance) {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return string
     */
    public function getBalance() {
        return $this->balance;
    }

    public function setEmail($email) {
        parent::setEmail($email);
        $this->setUsername($email);
    }

    public function __construct() {
        parent::__construct();
        $this->username = 'username';
        $this->currency = \Shop\CoreBundle\Services\Currency::DEFAULT_CURRENCY ;
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return UserAddress
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Add userAddress
     *
     * @param \Shop\UserBundle\Entity\UserAddress $userAddress
     * @return User
     */
    public function addUserAddress(\Shop\UserBundle\Entity\UserAddress $userAddress) {
        $this->userAddress[] = $userAddress;

        return $this;
    }

    public function addUserAddres(\Shop\UserBundle\Entity\UserAddress $userAddress) {
        return $this->addUserAddress($userAddress);
    }

    /**
     * Remove userAddress
     *
     * @param \Shop\UserBundle\Entity\UserAddress $userAddress
     */
    public function removeUserAddress(\Shop\UserBundle\Entity\UserAddress $userAddress) {
        $this->userAddress->removeElement($userAddress);
    }

    public function removeUserAddres(\Shop\UserBundle\Entity\UserAddress $userAddress) {
        removeUserAddress($userAddress);
    }

    /**
     * Get userAddress
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserAddress() {
        return $this->userAddress;
    }

    /* Alias of  getUserAddress */

    public function getUserAddresses() {
        return $this->getUserAddress();
    }


    /**
     * Add rentings
     *
     * @param \Services\CoreBundle\Entity\Renting $rentings
     * @return User
     */
    public function addRenting(\Services\CoreBundle\Entity\Renting $rentings)
    {
        $this->rentings[] = $rentings;

        return $this;
    }

    /**
     * Remove rentings
     *
     * @param \Services\CoreBundle\Entity\Renting $rentings
     */
    public function removeRenting(\Services\CoreBundle\Entity\Renting $rentings)
    {
        $this->rentings->removeElement($rentings);
    }

    /**
     * Get rentings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRentings()
    {
        return $this->rentings;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return User
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set sponsor
     *
     * @param \Sinenco\UserBundle\Entity\User $sponsor
     *
     * @return User
     */
    public function setSponsor(\Sinenco\UserBundle\Entity\User $sponsor = null)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor
     *
     * @return \Sinenco\UserBundle\Entity\User
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        if ( $this->balance == null ){
            $this->balance = 0 ; 
        }
    }

}
