<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="brands")
 * @property integer $id
 */
class Brand
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
    protected $from_name;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $from_email;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $reply_to;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $locale;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @var string
     */
    protected $app_key;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\Lists", mappedBy="brand")
     */
    protected $lists;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\Campaign", mappedBy="brand")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $campaigns;

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

    public function getLastCampaignTimestamp()
    {

    }

    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->name = (isset($data['name']))     ? $data['name']     : null;
    	$this->from_name = (isset($data['from_name']))     ? $data['from_name']     : null;
    	$this->from_email = (isset($data['from_email']))     ? $data['from_email']     : null;
    	$this->reply_to = (isset($data['reply_to']))     ? $data['reply_to']     : null;
    	$this->locale = (isset($data['locale']))     ? $data['locale']     : null;
    }
}
