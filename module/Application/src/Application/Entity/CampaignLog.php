<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity(repositoryClass="\Application\Repository\CampaignLog")
 * @ORM\Table(name="campaigns_log")
 * @property integer $id
 */
class CampaignLog
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
    protected $event;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $msg;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Subscriber", inversedBy="log")
     * @ORM\JoinColumn(name="email", referencedColumnName="email")
     */
    //private $subscriber;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime");
     * @var string
     */
    protected $occured_at;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Campaign", inversedBy="log")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    protected $campaign;

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
     * Constructor
     */
    public function __construct()
    {
    	$this->occured_at = new \DateTime();
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
    
    public function setEmail($email){
    	$this->email = $email;
    }

    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }

    public function exchangeArray($data)
    {

    }
}
