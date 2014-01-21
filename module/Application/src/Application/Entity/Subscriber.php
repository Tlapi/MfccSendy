<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity(repositoryClass="\Application\Repository\Subscribers")
 * @ORM\Table(name="subscribers")
 * @property integer $id
 */
class Subscriber
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
     * @ORM\Column(type="string");
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0});
     * @var string
     */
    protected $bounce_soft = 0;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0});
     * @var string
     */
    protected $bounced_hard = 0;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $bounce_message;

    /**
     * @ORM\Column(type="datetime");
     * @var string
     */
    protected $inserted_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Application\Entity\ListsToSubscribers", mappedBy="subscriber")
     */
    protected $lists_connection;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\CampaignLog", mappedBy="subscriber")
     */
    private $log;

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->inserted_at = new \DateTime();
    }

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
}
