<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CampaignStats Service
 */
class CampaignSender implements ServiceLocatorAwareInterface {

	const APP_TAG = 'machinegun';

	const METADATA_MESSAGE_TEST = 'testMessage';
	
	// deprecated
	const MESSAGE_TEST_TAG = 'mfsender_test';

	// deprecated
	const CAMPAIGN_TAG_PREFIX = 'mfsender_campaign_';

	// deprecated
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

		$_params = array(
				"template_name" => "id_".$this->campaign->id,
				"template_content" => $this->getTemplateContent(),
				"message" => $this->message,
				"async" => true,
		);
		$result = $this->mandrill->call('messages/send-template', $_params);

		foreach($result as $row){
			$testMail = new \Application\Entity\CampaignTests();
			$testMail->mandrill_id = $row['_id'];
			$testMail->email = $row['email'];
			$testMail->campaign = $this->campaign;
			$testMail->status = $row['status'];
			$this->getEntityManager()->persist($testMail);
		}

		$this->getEntityManager()->flush();
	}
	
	/**
	 * Prepare campaign
	 */
	public function prepareCampaign()
	{
		$writer = new \Zend\Log\Writer\Stream('log/mockup-sendmail.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		
		$logger->info('Starting prepareCampaign');
		/* lock the campaign */
		$this->campaign->status = \Application\Entity\Campaign::STATUS_PREPARING_LOCKED;
		$this->getEntityManager()->persist($this->campaign);
		$this->getEntityManager()->flush();
		
		$lists = implode(',',$this->campaign->recepient_lists);
		// TODO move to service method
		// TODO use constant
		// TODO get subscribers from repository function
		$targets = $this->getEntityManager()->createQuery("SELECT count(s)
				FROM Application\Entity\Subscriber AS s
				LEFT JOIN Application\Entity\ListsToSubscribers AS ls
				WITH s.id = ls.subscriber
				WHERE ls.list in ($lists)
				AND ls.status = 1
				GROUP BY s.email
				ORDER BY s.id ASC
				")->getResult();
		
		/* unlock and update the campaign */
		$this->campaign->status = \Application\Entity\Campaign::STATUS_PREPARING;
		$this->campaign->recipients = count($targets);
		$this->getEntityManager()->persist($this->campaign);
		$this->getEntityManager()->flush();
		$logger->info('End prepareCampaign');
	}

	/**
	 * Send campaign or resume sending
	 */
	public function sendCampaign()
	{
		$writer = new \Zend\Log\Writer\Stream('log/mockup-sendmail.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
				
		$max_messages = 10000;
		$logger->info('Starting sendCampaign, sending: '.$max_messages.' messages');
		$this->constructMessage();

		// SET some recipients
		//$this->setRecipients($recipients);
		$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
    	$lists = implode(',',$this->campaign->recepient_lists);
    	$last_id = $this->campaign->last_sent_id;

    	// TODO move to service method
    	// TODO use constant
    	// TODO get subscribers from repository function
    	$start_time = time();
    	
    	/* lock the campaign */
    	$this->campaign->status = \Application\Entity\Campaign::STATUS_SENDING_LOCKED;
    	$this->getEntityManager()->persist($this->campaign);
    	$this->getEntityManager()->flush();
    	
		$targets = $this->getEntityManager()->createQuery("SELECT s
				FROM Application\Entity\Subscriber AS s
				LEFT JOIN Application\Entity\ListsToSubscribers AS ls
				WITH s.id = ls.subscriber
				WHERE ls.list in ($lists)
				AND s.id > $last_id
				AND ls.status = 1
				GROUP BY s.email
				ORDER BY s.id ASC
				")->setMaxResults($max_messages)->getResult();
		echo '<br>took '.(time()-$start_time).' s to fetch recipients<br>';
		//var_dump($targets[0]->email);
		//var_dump($targets[0]->ls);
		//var_dump($targets[1][0]->id);
		//echo print_r($targets[1])."<br />";
		//echo print_r($targets[0][0]->cid)."<br />";
		//echo print_r($targets[0][1]);
    	echo 'sending to :'.count($targets);
    	//exit();

    	// Build array of recepients
    	$recepients = array();
    	$recipients_vars = array();
    	$count = 0;
    	foreach($targets as $target)
    	{
    		if(!filter_var($target->email, FILTER_VALIDATE_EMAIL))
    		{
    			echo '<br>Bad email encountered: "'.$target->email.'"';
    			$target->bounced_hard = 1;
    			$target->bounce_message = 'Machinegun: bad email!';
    			continue;
    		}
    		array_push($recepients, array(
    			"email" => $target->email,
    			"name" => $target->name
    		));

    		$mergeVars = array(
    				'rcpt' => $target->email,
    				'vars' => array(
    						array(
    								'name' => 'connection_id',
    								'content' => $target->id
    						)
    				)
    		);
    		$recipients_vars[] = $mergeVars;

    		$count++;
    		// TODO config for max messages
    		if($count > $max_messages)
    		{
    			break;
    		}
    	}

    	$this->message['merge_vars'] = $recipients_vars;

    	if(count($recepients))
    	{
    		// Send bulk
    		$this->setRecipients($recepients);
    		$_params = array(
    			"template_name" => "id_".$this->campaign->id,
    			"template_content" => $this->getTemplateContent(),
    			"message" => $this->message,
    			"async" => true,
   			);
    		echo "<br>sending $count";
    		$start_time = time();
    		$result = $this->mandrill->call('messages/send-template', $_params);
    		//$logger->info('JSON: '.json_encode($_params));
    		//echo 'not sending any data, TEST MODE';
    	   	echo '<br>took '.(time()-$start_time).' s to send data<br>';
    	   	//var_dump($_params);
    	   	// Save last sent ID when success
    	   	$this->campaign->last_sent_id = $target->id;
    	   	
    	   	/* unlock the campaign*/
    	   	$this->campaign->status = \Application\Entity\Campaign::STATUS_SENDING;
    	} else {
    		// Set campaign sent
    		$this->campaign->status = \Application\Entity\Campaign::STATUS_SENT;
    		$this->campaign->changed_at = new \DateTime();
    	}
    	$this->getEntityManager()->persist($this->campaign);
    	$this->getEntityManager()->flush();
		echo 'last sent id :'.$target->id;
		//echo json_encode($result);
		$logger->info('End sendCampaign');
    	//die($result);
    	//exit();
	}

	/**
	 * Send campaign or resume sending
	 */
	public function uploadTemplate()
	{
		$template = $this->constructTemplate();
		try {
			$this->mandrill->call('templates/add', $template);
		} catch (\Mandrill_Invalid_Template $e){
			$this->mandrill->call('templates/update', $template);
		}

	}

	/**
	 * Construct mandrill message
	 * @return Mandrill message
	 */
	public function constructMessage($test = false)
	{
		/*
		$plugins = $this->getServiceLocator()->get('ViewHelperManager');
		if($test){
			// Add tracking image for fast response
			$this->campaign->html_text = $this->campaign->html_text.'<img alt="" src="'.$plugins->get('url')->__invoke('hooks/open-message', array('id' => 'ID'), array('force_canonical' => true)).'" />';
		}*/
		$this->message['track_opens'] = true;
		$this->message['track_clicks'] = true;
		$this->message['merge'] = true;
		// don't show other recipients
		$this->message['preserve_recipients'] = false;
		$this->message['metadata']['campaignId'] = $this->campaign->id;

		$this->message['tags'] = array(self::APP_TAG);
		
		if($test) {
			$this->message['metadata'][self::METADATA_MESSAGE_TEST] = true;
			$this->message['global_merge_vars'] = array(
				array(
					'name' => 'connection_id',
					'content' => 'testing'
				),
				array(
					'name' => 'campaign_id',
					'content' => $this->campaign->id.'?testing'
				)
			);
		} else {
			$this->message['metadata']['brandId'] = $this->campaign->brand->id;
			$this->message['global_merge_vars'] = array(
					array(
							'name' => 'campaign_id',
							'content' => $this->campaign->id
					)
			);
		}

		return $this->message;
	}

	/**
	 * Construct Mandrill template
	 */
	public function constructTemplate()
	{
		$template = array(
				"name" => 'id_'.$this->campaign->id,
				"from_email" => $this->campaign->from_email,
				"from_name" => $this->campaign->from_name,
				"subject" => $this->campaign->subject,
				"code" => $this->addUnsubcribeLink($this->campaign->html_text),
				"text" => $this->campaign->plain_text,
				"publish" => true,
		);

		return $template;
	}

	/**
	 * Adds unsubscribe link to template
	 */
	public function addUnsubcribeLink()
	{
		$plugins = $this->getServiceLocator()->get('ViewHelperManager');
		//$template_code = $this->campaign->html_text.'<br /><a href="'. .'*|connection_id|*/*|campaign_id|*">Unsubscribe</a>';
		$href = $plugins->get('url')->__invoke('public/unsubscribe', array(), array('force_canonical' => true));
		
		$template_code = $this->campaign->html_text;
		$template_code = str_replace('/unsubscribe','/a',$template_code);
		$template_code = str_replace('unsubscribe','a href="'.$href.'*|connection_id|*/*|campaign_id|*"',$template_code);
		return $template_code;
	}

	/**
	 * Get template content
	 */
	public function getTemplateContent()
	{
		return '';
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
