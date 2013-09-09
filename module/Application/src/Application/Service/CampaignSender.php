<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CampaignStats Service
 */
class CampaignSender implements ServiceLocatorAwareInterface {

	const MESSAGE_TEST_TAG = 'mfsender_test';
	
	const CAMPAIGN_TAG_PREFIX = 'campaign_';
	
	const BRAND_TAG_PREFIX = 'brand_';
	
	/**
	 * @var \Mandrill;
	 */
	protected $mandrill;

	/**
	 * @var \Application\Entity\Campaign;
	 */
	protected $campaign;

	/**
	 *
	 * @var array
	 */
	private $message;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Test send campaign
	 * @param array $emails
	 */
	public function sendTest($emails = array())
	{
		$this->constructMessage(true);

		$recipients = array();
		foreach($emails as $email){
			$recipients[]['email'] = $email;
		}
		$this->setRecipients($recipients);

		$this->mandrill->messages->send(
			$this->message,
			true
		);

		foreach($recipients as $recipient){
			$testMail = new \Application\Entity\CampaignTests();
			$testMail->email = $recipient['email'];
			$testMail->campaign = $this->campaign;
			$this->getEntityManager()->persist($testMail);
		}

		$this->getEntityManager()->flush();
	}

	/**
	 * Send campaign or resume sending
	 */
	public function sendCampaign()
	{
		$this->constructMessage();

		// SET some recipients
		//$this->setRecipients($recipients);
		$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
    	$lists = implode(' ',$this->campaign->recepient_lists);
    	$last_id=$this->campaign->last_sent_id;
		$targets = $this->getEntityManager()->createQuery(
				
		"SELECT s FROM Application\Entity\Subscriber AS s LEFT JOIN Application\Entity\ListsToSubscribers AS ls WITH s.id=ls.subscriber WHERE ls.list in ($lists) AND s.id>$last_id GROUP BY s.email ORDER BY s.id DESC"
				)->getResult();
		
    	echo 'sending to :'.count($targets);
		
    	$to=array();
    	$count=0;
    	foreach($targets as $target)
    	{	array_push($to, array("email"=>$target->email,"name"=>$target->name));
    		$campaign->last_sent_id=$target->id;
    		$count++;
    		// TODO config for max messages
    		if($count>500)
    		{	break;
    		}
    	}
    	if($count>0)
    	{	$this->setRecipients($to);
    		$_params = array(
    			"template_name" => "id_".$this->campaign->id, 
    			"template_content" => $template_content,
    			"message" => $this->message,
    			"async" => true,
   			);
    		echo "sending $count";
    	   	$result=$this->mandrill->call('messages/send-template', $_params);
    	}    		
    	
    	echo json_encode($result);
    	die($result);
    	
	}
	
	/**
	 * Send campaign or resume sending
	 */
	public function uploadTemplate()
	{
		$template=$this->constructTemplate();
		$this->mandrill->call('templates/add', $template);
		// SET some recipients
		//$this->setRecipients($recipients);
	
		/*
			$this->mandrill->messages->send(
					$this->message,
					true
			);*/
	
	}

	/**
	 * Constrict mandrill message
	 * @return Mandrill message
	 */
	public function constructMessage($test = false)
	{	/* set in template
		$this->message['html'] = $this->campaign->html_text;
		$this->message['text'] = $this->campaign->plain_text;
		$this->message['subject'] = $this->campaign->subject;

		$this->message['from_email'] = $this->campaign->from_email;
		$this->message['track_opens'] = true;
		$this->message['track_clicks'] = true;
		$this->message['from_email'] = $this->campaign->from_email;*/
		$this->message['track_opens'] = true;
                $this->message['track_clicks'] = true;
		// don't show other recipients
		$this->message['preserve_recipients'] = false;

		if($test)
			$this->message['tags'] = array(self::MESSAGE_TEST_TAG, self::CAMPAIGN_TAG_PREFIX.$this->campaign->id);
		else
			$this->message['tags'] = array(self::BRAND_TAG_PREFIX.$this->campaign->brand->id, self::CAMPAIGN_TAG_PREFIX.$this->campaign->id);

		return $this->message;
	}
	
	public function constructTemplate()
	{	$template = array("name" => 'id_'.$this->campaign->id,
					 "from_email" => $this->campaign->from_email,
					 "from_name" => $this->campaign->from_name,
					 "subject" => $this->campaign->subject,
					 "code" => $campaign->html_text,
					 "text" => $campaign->plain_text,
					 "publish" => true,
				);
		return $template;
		
	}

	/**
	 * Clear message recipients
	 */
	public function clearRecipients()
	{
		$this->message['to'] = array();
	}

	/**
	 * Set message recipients
	 * @param array $recipients array of email and name keys
	 */
	public function setRecipients($recipients = array())
	{
		$this->message['to'] = $recipients;
	}

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/* Mandrill setter and getter */

	public function setMandrill(\Mandrill $mandrill) {
		$this->mandrill = $mandrill;
		$this->webhooksList = $this->mandrill->webhooks->getList();
	}

	public function getMandrill() {
		return $this->mandrill;
	}

	/* Campaign setter and getter */

	public function setCampaign(\Application\Entity\Campaign $campaign) {
		$this->campaign = $campaign;
	}

	public function getCampaign() {
		return $this->campaign;
	}

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}

}
