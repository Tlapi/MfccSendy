<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="campaigns")
 * @property integer $id
 */
class Campaign
{

	const STATUS_DRAFT = 0;
	const STATUS_PREPARING = 1;
	const STATUS_SENDING = 2;
	const STATUS_SENT = 3;
	const STATUS_ERROR = 4;
	const STATUS_SENDING_LOCKED = 5;
	const STATUS_PREPARING_LOCKED = 6;
	
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
     * @ORM\Column(type="string");
     * @var string
     */
    protected $subject;

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
     * @ORM\Column(type="array", nullable=true);
     * @var array
     */
    protected $os;

    /**
     * @ORM\Column(type="array", nullable=true);
     * @var array
     */
    protected $ua;

    /**
     * @ORM\Column(type="array", nullable=true);
     * @var array
     */
    protected $location;

    /**
     * @ORM\Column(type="datetime", nullable=true);
     * @var string
     */
    protected $sent_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true);
     * @var string
     */
    protected $changed_at;

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
     * @ORM\OneToMany(targetEntity="Application\Entity\CampaignTests", mappedBy="campaign", cascade={"remove"}, orphanRemoval=true)
     */
    private $tests;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\CampaignLog", mappedBy="campaign", cascade={"remove"}, orphanRemoval=true)
     */
    private $log;

    /**
     * @ORM\Column(type="integer", options={"default" = 0});
     * @var int
     * 0 - draft
     * 1 - preparing
     * 2 - sending
     * 3 - sent
     * 4 - error
     */
    protected $status;

    /**
     * @ORM\Column(type="integer", options={"default" = 0});
     * @var int
     */
    protected $last_sent_id;

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->status = self::STATUS_DRAFT;
    	$this->last_sent_id = 0;
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

    /**
     * Get last opened mails from log
     */
    public function getLastOpened()
    {
    	return $this->filterLatestLog('open');
    }

    /**
     * Get last clicked mails from log
     */
    public function getLastClicked()
    {
    	return $this->filterLatestLog('click');
    }

    /**
     * Get last clicked mails from log
     */
    public function getLastBounced()
    {
    	return $this->filterLatestLog(array('hard_bounce', 'soft_bounce'));
    }

    /**
     * Get last clicked mails from log
     */
    public function getLastSpam()
    {
    	return $this->filterLatestLog('spam');
    }

    /**
     * Get last 10 records for provided event ordered by timestamp
     * @mixed $event
     */
    public function filterLatestLog($event)
    {
    	$criteria = Criteria::create();
    	if(is_array($event)){
    		foreach($event as $e){
    			$criteria->orWhere(Criteria::expr()->eq("event", $e));
    		}
    	} else {
    		$criteria->where(Criteria::expr()->eq("event", $event));
    	}

    	$criteria->orderBy(array("occured_at" => Criteria::DESC))
    	->setMaxResults(10);
    	return $this->log->matching($criteria);
    }

    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
    	$this->title = (isset($data['title']))     ? $data['title']     : null;
    	$this->subject = (isset($data['subject']))     ? $data['subject']     : null;
    	$this->from_name = (isset($data['from_name']))     ? $data['from_name']     : null;
    	$this->from_email = (isset($data['from_email']))     ? $data['from_email']     : null;
    	$this->reply_to = (isset($data['reply_to']))     ? $data['reply_to']     : null;
    	$this->plain_text = (isset($data['plain_text']))     ? $data['plain_text']     : null;
    	$this->html_text = (isset($data['html_text']))     ? $data['html_text']     : null;
    	$this->status = (isset($data['status']))     ? $data['status']     : null;
    	$this->last_sent_id = (isset($data['last_sent_id']))     ? $data['last_send_id']     : null;
    }
    
    /**
     * Get some stats formated tu use in charts
     */
 	public function getFormatedOsStats(){
 		$os = array();
 		$os_cats = array();
 		$os_total = 0;
 		foreach($this->os as $family => $names){
 			foreach($names as $name => $counter){
 				$os[$family]['count'] += $counter;
 				$os[$family]['versions'][$name] = $counter;
 				$os_total += $counter;
 			}
 			arsort($os[$family]['versions']);
 		}
 		// Convert to percents
 		foreach($os as $family => $names){
 			$os[$family]['percent'] = round(($os[$family]['count'] / $os_total) * 100, 2);
 			foreach($os[$family]['versions'] as $name => $counter){
 				$ct = $os[$family]['versions'][$name];
 				unset($os[$family]['versions'][$name]);
 				$os[$family]['versions'][$name]['count'] = $ct;
 				$os[$family]['versions'][$name]['percent'] = round(($os[$family]['versions'][$name]['count'] / $os_total) * 100, 2);
 			}
 		}
 		arsort($os);
 		foreach($os as $family => $names){
 			$os_cats[] = "'".($family?$family:'Unknown')."'";
 		}
 		
 		return array(
 			'list' => $os,	
 			'cats' => $os_cats,	
 			'total' => $os_total,	
 		);
 	}
 	   
 	public function getFormatedClientsStats(){
 		$clients = array();
    	$clients_cats = array();
    	$clients_total = 0;
    	foreach($this->ua as $family => $names){
    		foreach($names as $name => $counter){
    			$name = $family." ".$name;
    			if($family=='Outlook 2007' || $family=='Outlook 2010' ){
    				$name = $family;
    				$family = 'Outlook';
    			}
    			$clients[$family]['count'] += $counter;
    			$clients[$family]['versions'][$name] = $counter;
    			$clients_total += $counter;
    		}
    		arsort($clients[$family]['versions']);
    	}
    	// Convert to percents
    	foreach($clients as $family => $names){
    		$clients[$family]['percent'] = round(($clients[$family]['count'] / $clients_total) * 100, 2);
    		foreach($clients[$family]['versions'] as $name => $counter){
    			$ct = $clients[$family]['versions'][$name];
    			unset($clients[$family]['versions'][$name]);
    			$clients[$family]['versions'][$name]['count'] = $ct;
    			$clients[$family]['versions'][$name]['percent'] = round(($clients[$family]['versions'][$name]['count'] / $clients_total) * 100, 2);
    		}
    	}
    	arsort($clients);
    	foreach($clients as $family => $names){
    		$clients_cats[] = "'".($family?$family:'Unknown')."'";
    	}
 		
 		return array(
 			'list' => $clients,	
 			'cats' => $clients_cats,	
 			'total' => $clients_total,	
 		);
 	}
 	   
 	public function getFormatedLocationStats(){
 		$locations = array();
    	$locations_cats = array();
    	$locations_total = 0;
    	foreach($this->location as $country => $regions){
    		foreach($regions as $region => $counter){
    			$locations[$country]['count'] += $counter;
    			$locations[$country]['regions'][$region] = $counter;
    			$locations_total += $counter;
    		}
    		arsort($locations[$country]['regions']);
    	}
    	// Convert to percents
    	foreach($locations as $country => $regions){
    		$locations[$country]['percent'] = round(($locations[$country]['count'] / $locations_total) * 100, 2);
    		foreach($locations[$country]['regions'] as $region => $counter){
    			$ct = $locations[$country]['regions'][$region];
    			unset($locations[$country]['regions'][$region]);
    			$locations[$country]['regions'][$region]['count'] = $ct;
    			$locations[$country]['regions'][$region]['percent'] = round(($locations[$country]['regions'][$region]['count'] / $locations_total) * 100, 2);
    		}
    	}
    	arsort($locations);
    	foreach($locations as $country => $regions){
    		$locations_cats[] = "'".($country?$country:'Unknown')."'";
    	}
 		
 		return array(
 			'list' => $locations,	
 			'cats' => $locations_cats,	
 			'total' => $locations_total,	
 		);
 	}   
}
