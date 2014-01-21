<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * A student
 *
 * @ORM\Entity(repositoryClass="\Application\Repository\Lists")
 * @ORM\Table(name="lists")
 * @property integer $id
 */
class Lists
{
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $sub_success_page;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $sub_confirmed_page;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $unsub_page;
    
    /**
     * @ORM\Column(type="boolean", nullable=true);
     * @var boolean
     */
    protected $unsub_from_all;
    
    /**
     * @ORM\Column(type="boolean", nullable=true);
     * @var boolean
     */
    protected $unsub_send;
    
    /**
     * @ORM\Column(type="boolean", nullable=true);
     * @var boolean
     */
    protected $double_optin;
    
    /**
     * @ORM\Column(type="boolean", nullable=true);
     * @var boolean
     */
    protected $thanks_send;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $thanks_subject;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $thanks_message;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $conf_subject;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $conf_message;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $unsub_subject;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $unsub_message;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Brand", inversedBy="lists")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Lists")
     * @ORM\JoinColumn(name="merged_into", referencedColumnName="id")
     */
    private $merged_into_list;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Application\Entity\ListsToSubscribers", mappedBy="list")
     */
    protected $subsribers_connection;

    /**
     * Magic getter to expose protected properties.
     *
     * @param DateTime $property
     * @return mixed
     */
    public function __get($property)
    {
    	return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
    	$this->$property = $value;
    }

    /**
     * Gets collection of active users
     */
    public function getActiveUsers()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("status", 1));
    	return $this->subsribers_connection->matching($criteria);
    }
    
    /**
     * Gets collection of unsubscribed users
     */
    public function getUnsubscribedUsers()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("status", 2));
    	return $this->subsribers_connection->matching($criteria);
    }

    /**
     * Gets collection of unsubscribed users
     */
    public function getSoftBouncedUsers()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("status", 3));
    	return $this->subsribers_connection->matching($criteria);
    }

    /**
     * Gets collection of unsubscribed users
     */
    public function getHardBouncedUsers()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("status", 4));
    	return $this->subsribers_connection->matching($criteria);
    }

    /**
     * Gets collection of unsubscribed users
     */
    public function getComplainedUsers()
    {
    	$criteria = Criteria::create()->where(Criteria::expr()->eq("status", 5));
    	return $this->subsribers_connection->matching($criteria);
    }

    /**
     * Get users subcribed in provided month in provided year
     * @param int $month
     * @param int $year
     */
    public function getSubsribedInMonth($month, $year)
    {

    }


    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->name = (isset($data['name']))     ? $data['name']     : null;
    	$this->double_optin = (isset($data['double_optin']))     ? $data['double_optin']     : null;
    	$this->sub_success_page = (isset($data['sub_success_page']))     ? $data['sub_success_page']     : null;
    	$this->sub_confirmed_page = (isset($data['sub_confirmed_page']))     ? $data['sub_confirmed_page']     : null;
    	$this->unsub_from_all = (isset($data['unsub_from_all']))     ? $data['unsub_from_all']     : null;
    	$this->unsub_page = (isset($data['unsub_page']))     ? $data['unsub_page']     : null;
    	$this->thanks_send = (isset($data['thanks_send']))     ? $data['thanks_send']     : null;
    	$this->thanks_subject = (isset($data['thanks_subject']))     ? $data['thanks_subject']     : null;
    	$this->thanks_message = (isset($data['thanks_message']))     ? $data['thanks_message']     : null;
    	$this->conf_subject = (isset($data['conf_subject']))     ? $data['conf_subject']     : null;
    	$this->conf_message = (isset($data['conf_message']))     ? $data['conf_message']     : null;
    	$this->unsub_send = (isset($data['unsub_send']))     ? $data['unsub_send']     : null;
    	$this->unsub_subject = (isset($data['unsub_subject']))     ? $data['unsub_subject']     : null;
    	$this->unsub_message = (isset($data['unsub_message']))     ? $data['unsub_message']     : null;
    }
}
