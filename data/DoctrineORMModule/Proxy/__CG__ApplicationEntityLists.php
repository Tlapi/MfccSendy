<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Lists extends \Application\Entity\Lists implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function __get($property)
    {
        $this->__load();
        return parent::__get($property);
    }

    public function __set($property, $value)
    {
        $this->__load();
        return parent::__set($property, $value);
    }

    public function getActiveUsers()
    {
        $this->__load();
        return parent::getActiveUsers();
    }

    public function getUnsubscribedUsers()
    {
        $this->__load();
        return parent::getUnsubscribedUsers();
    }

    public function getSoftBouncedUsers()
    {
        $this->__load();
        return parent::getSoftBouncedUsers();
    }

    public function getHardBouncedUsers()
    {
        $this->__load();
        return parent::getHardBouncedUsers();
    }

    public function getComplainedUsers()
    {
        $this->__load();
        return parent::getComplainedUsers();
    }

    public function getSubsribedInMonth($month, $year)
    {
        $this->__load();
        return parent::getSubsribedInMonth($month, $year);
    }

    public function getArrayCopy()
    {
        $this->__load();
        return parent::getArrayCopy();
    }

    public function exchangeArray($data)
    {
        $this->__load();
        return parent::exchangeArray($data);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'brand', 'subsribers_connection');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}