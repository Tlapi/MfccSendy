<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="campaigns")
 * @property integer $id
 */
class Campaign
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
    protected $from_name;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $from_email;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $reply_to;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text");
     * @var string
     */
    protected $plain_text;

    /**
     * @ORM\Column(type="text");
     * @var string
     */
    protected $html_text;

    /**
     * @ORM\Column(type="array", nullable=true);
     * @var array
     */
    protected $recepient_lists;

    /**
     * @ORM\Column(type="datetime", nullable=true);
     * @var string
     */
    protected $sent_at;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var string
     */
    protected $amount_sent;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @var string
     */
    protected $recipients;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Brand", inversedBy="campaigns")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

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
    	$this->title = (isset($data['title']))     ? $data['title']     : null;
    	$this->from_name = (isset($data['from_name']))     ? $data['from_name']     : null;
    	$this->from_email = (isset($data['from_email']))     ? $data['from_email']     : null;
    	$this->reply_to = (isset($data['reply_to']))     ? $data['reply_to']     : null;
    	$this->plain_text = (isset($data['plain_text']))     ? $data['plain_text']     : null;
    	$this->html_text = (isset($data['html_text']))     ? $data['html_text']     : null;
    }
}
