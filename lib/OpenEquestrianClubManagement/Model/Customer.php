<?php

namespace OpenEquestrianClubManagement\Model;

use OpenEquestrianClubManagement\Model\om\BaseCustomer;


/**
 * Skeleton subclass for representing a row from the 'customer' table.
 *
 * Table de gestion des clients
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.OpenEquestrianClubManagement.Model
 */
class Customer extends BaseCustomer
{
    /**
     * Build the show name (use for slug) : "lastname firstname"
     *
     * @return  string  the show_name
     * @access  private
     */
    private function buildShowName()
    {
        return $this->getLastname().' '.$this->getFirstname();
    }
    
    
    /**
     * Set the value of [firstname] column.
     * Prénom du client
     * @param string $v new value
     * @return Customer The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        parent::setFirstname($v);
        
        $this->setShowName($this->buildShowName());
        
        return $this;
    }
    
    
    /**
     * Set the value of [lastname] column.
     * Nom du client
     * @param string $v new value
     * @return Customer The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        parent::setLastname($v);
        
        $this->setShowName($this->buildShowName());
        
        return $this;
    }
    
    
    /**
     * Return the first phone number
     *
     * @return  string|null     The first phone number
     * @access  public
     */
    public function getPhone0()
    {
        $phones = $this->getPhones();
        if (is_object($phones) === true && isset($phones->phone0) === true) {
            return $phones->phone0;
        }
        
        return null;
    }
    
    
    /**
     * Set the first phone number
     *
     * @param   string  $v  the value of the first phone number
     * @return  $this
     * @access  public
     */
    public function setPhone0($v)
    {
        $phone0 = $this->getPhone0();
        if ($phone0 !== $v) {
            $tmp_phones = $this->getPhones();
            if (is_object($tmp_phones) === false) {
                $phones = new \stdClass();
            } else {
                $phones = clone $tmp_phones;
            }
            
            $phones->phone0 = (string)$v;
            
            $this->setPhones($phones);
        }
        
        return $this;
    }
    
    
    /**
     * Return the second phone number
     *
     * @return  string|null     The second phone number
     * @access  public
     */
    public function getPhone1()
    {
        $phones = $this->getPhones();
        if (is_object($phones) === true && isset($phones->phone1) === true) {
            return $phones->phone1;
        }
        
        return null;
    }
    
    
    /**
     * Set the second phone number
     *
     * @param   string  $v  the value of the second phone number
     * @return  $this
     * @access  public
     */
    public function setPhone1($v)
    {
        $phone1 = $this->getPhone1();
        if ($phone1 !== $v) {
            $tmp_phones = clone $this->getPhones();
            if (is_object($tmp_phones) === false) {
                $phones = new \stdClass();
            } else {
                $phones = clone $tmp_phones;
            }
            
            $phones->phone1 = (string)$v;
            
            $this->setPhones($phones);
        }
        
        return $this;
    }
    
    
    /**
     * Return the third phone number
     *
     * @return  string|null     The third phone number
     * @access  public
     */
    public function getPhone2()
    {
        $phones = $this->getPhones();
        if (is_object($phones) === true && isset($phones->phone2) === true) {
            return $phones->phone2;
        }
        
        return null;
    }
    
    
    /**
     * Set the third phone number
     *
     * @param   string  $v  the value of the third phone number
     * @return  $this
     * @access  public
     */
    public function setPhone2($v)
    {
        $phone2 = $this->getPhone2();
        if ($phone2 !== $v) {
            $tmp_phones = clone $this->getPhones();
            if (is_object($tmp_phones) === false) {
                $phones = new \stdClass();
            } else {
                $phones = clone $tmp_phones;
            }
            
            $phones->phone2 = (string)$v;
            
            $this->setPhones($phones);
        }
        
        return $this;
    }
}
