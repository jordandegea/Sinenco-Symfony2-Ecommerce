<?php
 
namespace Shop\CartBundle\Entity\Traits;
 
use Doctrine\ORM\Mapping as ORM;
 
trait CartOptionsTrait{

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $configuration;

    public function __initCartOptionsTrait(){
        if ( $this->configuration == null ) {
            $this->configuration = [0 => true];
        }
    }
    public function __onPersistOrUpdate(){
        if ( $this->configuration == null ) {
            $this->configuration = [0 => true];
        }
    }
    
    /**
     * Set configuration
     *
     * @param array $configuration
     * @return CartItem
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return array 
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    
    public function setFirstTime( $isTrue ){
        $this->configuration[0] = $isTrue ;
    }
    
    public function addConfiguration($value, $key1, $key2 = null, $key3 = null ){
        
        if ( ! array_key_exists($key1, $this->configuration) ){
            if ( $key2 == null ){
                $this->configuration[$key1] = $value ;
                return true ; 
            }
        }
           
        if ( $this->configuration[$key1] == null || ! array_key_exists($key2, $this->configuration[$key1]) ){
            if ( $key3 == null ){
                $this->configuration[$key1][$key2] = $value ;
                return true ; 
            }
        }
        
        if ( $this->configuration[$key1][$key2] == null || ! array_key_exists($key3, $this->configuration[$key1][$key2]) ){
            $this->configuration[$key1][$key2][$key3] = $value ;
            return true ; 
        }
        return false;
    }
    
    
    public function forceAdd($value, $key1, $key2 = null, $key3 = null ){
        if ( $key2 == null ){
            $this->configuration[$key1] = $value ;
            return true ; 
        }

        if ( $key3 == null ){
            $this->configuration[$key1][$key2] = $value ;
            return true ; 
        }
        
        $this->configuration[$key1][$key2][$key3] = $value ;
        return true ;
    }
    
    
    
}