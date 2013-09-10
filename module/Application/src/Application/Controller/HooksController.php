<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HooksController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {

    	$writer = new \Zend\Log\Writer\Stream('log/hooks.txt');
    	$logger = new \Zend\Log\Logger();
    	$logger->addWriter($writer);

    	$mandrillWebhookData = json_decode($_POST['mandrill_events']);

    	//$logger->info('CALLED: '.$_POST['mandrill_events']);

    	$pubnub = $this->getServiceLocator()->get('pubnubService');

    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    	$campaignTests = $this->getEntityManager()->getRepository('Application\Entity\CampaignTests');

    	// Parse Mandrill Webhook events
    	foreach ($mandrillWebhookData as $event){
    		$logger->info($event->msg->email.' | '.$event->event.' | '.join('-',$event->msg->tags));

    		if(isset($event->msg->email)){

	    		if(in_array(\Application\Service\CampaignSender::MESSAGE_TEST_TAG, $event->msg->tags)){
	    			// Test message
	    			if($event->event=='open'){
	    				$testMail = $campaignTests->findOneBy(array('email' => $event->msg->email, 'campaign' => $this->getCampaignIdFromTags($event->msg->tags)));
	    				$testMail->opened = new \DateTime(date('Y-m-d H:i:s', $event->ts));
	    				$this->getEntityManager()->persist($testMail);
	    			}
	    			if($event->event=='click'){
	    				$testMail = $campaignTests->findOneBy(array('email' => $event->msg->email, 'campaign' => $this->getCampaignIdFromTags($event->msg->tags)));
	    				$testMail->clicked = new \DateTime(date('Y-m-d H:i:s', $event->ts));
	    				$this->getEntityManager()->persist($testMail);
	    			}

	    			// Live feed
	    			$info = $pubnub->publish(array(
	    					'channel' => 'mfcc_sender_test_event', ## REQUIRED Channel to Send
	    					'message' => json_encode(array(
	    							'email' => $event->msg->email,
	    							'ts' => $event->ts,
	    							'event' => $event->event
	    					))
	    			));
	    		} else {
	    			// Production message

	    			// TODO temporary get campaign num 1
	    			$campaign = $campaigns->find($this->getCampaignIdFromTags($event->msg->tags));

	    			if($campaign){
		    			// Log event to db
		    			$logRow = new \Application\Entity\CampaignLog();
		    			$logRow->event = $event->event;
		    			$logRow->email = $event->msg->email;
		    			$logRow->msg = $event->msg->bounce_description;
		    			$logRow->campaign = $campaign;

		    			if($event->event=='open'){
		    				// User Agent
		    				$ua = $campaign->ua;
		    				if(isset($ua[$event->user_agent_parsed->ua_family][$event->user_agent_parsed->ua_version]))
		    					$ua[$event->user_agent_parsed->ua_family][$event->user_agent_parsed->ua_version]++;
		    				else
		    					$ua[$event->user_agent_parsed->ua_family][$event->user_agent_parsed->ua_version] = 1;
		    				$campaign->ua = $ua;

		    				// OS
		    				$os = $campaign->os;
		    				if(isset($os[$event->user_agent_parsed->os_family][$event->user_agent_parsed->os_name]))
		    					$os[$event->user_agent_parsed->os_family][$event->user_agent_parsed->os_name]++;
		    				else
		    					$os[$event->user_agent_parsed->os_family][$event->user_agent_parsed->os_name] = 1;
		    				$campaign->os = $os;

		    				// Location
		    				// TODO handle city
		    				$location = $campaign->location;
		    				if(isset($location[$event->location->country][$event->location->region]))
		    					$location[$event->location->country][$event->location->region]++;
		    				else
		    					$location[$event->location->country][$event->location->region] = 1;
		    				$campaign->location = $location;

		    				$this->getEntityManager()->persist($campaign);
		    			}

		    			$this->getEntityManager()->persist($logRow);

		    			// Live feed
		    			$info = $pubnub->publish(array(
		    					'channel' => 'mfcc_sender_event', ## REQUIRED Channel to Send
		    					'message' => json_encode(array(
		    							'email' => $event->msg->email,
		    							'ts' => $event->ts,
		    							'latitude' => $event->location->latitude,
		    							'longitude' => $event->location->longitude,
		    							'event' => $event->event
		    					))
		    			));
	    			}
	    		}

    		}
    	}

    	$this->getEntityManager()->flush();

    	die('hook');

        return new ViewModel(array(

        ));
    }

    public function setAction()
    {
    	// TODO move to factory
    	$webhooks = $this->getServiceLocator()->get('webhooks');

    	$webhooks->addWebhook();

		$this->redirect()->toRoute('home');
    }

    /**
     * Get Campaign ID from Mandrill tags
     */
    public function getCampaignIdFromTags($tags = array())
    {
    	foreach($tags as $tag){
    		if (strpos($tag, \Application\Service\CampaignSender::CAMPAIGN_TAG_PREFIX) !== false) {
    			return str_replace(\Application\Service\CampaignSender::CAMPAIGN_TAG_PREFIX, '', $tag);
    		}
    	}
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
