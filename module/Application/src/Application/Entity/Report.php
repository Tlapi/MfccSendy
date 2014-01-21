<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="reports")
 * @property integer $id
 */
class Report
{
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    protected $campaign;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $password;

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
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
    public function exchangeArray($data)
    {
    	$this->password = (isset($data['password'])) ? md5($data['password']) : null;
    }
}
