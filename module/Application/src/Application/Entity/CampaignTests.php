<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="campaigns_tests")
 * @property integer $id
 */
class CampaignTests
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
    protected $mandrill_id;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime");
     * @var string
     */
    protected $sent_at;

    /**
     * @ORM\Column(type="datetime", nullable=true);
     * @var string
     */
    protected $opened;

    /**
     * @ORM\Column(type="datetime", nullable=true);
     * @var string
     */
    protected $clicked;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Campaign", inversedBy="tests")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    private $campaign;

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
    	$this->sent_at = new \DateTime();
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
