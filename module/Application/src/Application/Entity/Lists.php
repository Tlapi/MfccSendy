<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * A student
 *
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="Application\Entity\Brand", inversedBy="lists")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

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
    }
}
