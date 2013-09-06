<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity(repositoryClass="\Application\Repository\ListsToSubscribers")
 * @ORM\Table(name="lists_to_subscribers")
 * @property integer $id
 */
class ListsToSubscribers
{
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Subscriber", inversedBy="lists_connection")
     * @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id")
     */
    private $subscriber;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Lists", inversedBy="subsribers_connection")
     * @ORM\JoinColumn(name="list_id", referencedColumnName="id")
     */
    private $list;

    /**
     * @ORM\Column(type="integer", options={"default" = 0});
     * @var int
     * 0 - unknown
     * 1 - subscribed
     * 2 - unsubsribed
     * 3 - soft bounced
     * 4 - hard bounced
     * 5 - complained
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime");
     * @var string
     */
    protected $subscribed_at;

    /**
     * @ORM\Column(type="datetime");
     * @var string
     */
    protected $last_activity_at;

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


    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }

    public function exchangeArray($data)
    {

    }
}
