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

class CronController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {
		/* TODO move to factory */
    	$writer = new \Zend\Log\Writer\Stream('log/cron.log');
    	$logger = new \Zend\Log\Logger();
    	$logger->addWriter($writer);
    	$logger->info('Cron Start');
    	
    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    	
    	/* first try to send prepared campaigns
    	 * preparing a campaign and sending it in one step can cause errors due to unfinished preparing cron job being started over and setting 
    	 */
    	
    	// Get ONE active campaign and send or continue sending
    	$campaign = $campaigns->findOneBy(array('status' => \Application\Entity\Campaign::STATUS_SENDING));
    	 
    	if($campaign!=null)
    	{	$logger->info('Sending campaign: '.$campaign->id.' '.$campaign->title);
	    	$campaignSender = $this->getServiceLocator()->get('campaignSender');
	    	$campaignSender->setCampaign($campaign);
	    	// TODO set Di
	    	$campaignSender->sendCampaign();
	    	$id = $campaign->id;
	    	$sent = $campaigns->findOneBy(array('id'=>$id));
	    	
	    	/* log sending info */
	    	if($sent->status == \Application\Entity\Campaign::STATUS_SENDING) $logger->info('Campaign part sent: '.$sent->id.' '.$sent->title.'. Last sent id: '.$sent->last_sent_id);
	    	elseif($sent->status == \Application\Entity\Campaign::STATUS_SENT) $logger->info('Campaign sending finished: '.$sent->id.' '.$sent->title.'. Last sent id: '.$sent->last_sent_id);
	    	else $logger->err('Campaign sending error: '.$sent->id.' '.$sent->title.'. Last sent id: '.$sent->last_sent_id.' Status: '.$sent->status);
    	}
    	$logger->info('Cron End.');
    	
    	// Get ONE preparing campaing and upload the template to mandrill
    	$campaign = $campaigns->findOneBy(array('status' => \Application\Entity\Campaign::STATUS_PREPARING));
    	
    	if($campaign!=null)
    	{	
    		$logger->info('Uploading template for campaign: '.$campaign->id.' '.$campaign->title);
    		$campaignSender = $this->getServiceLocator()->get('campaignSender');
    		$campaignSender->setCampaign($campaign);
			
    		try {
    			$campaignSender->prepareCampaign();
    			$campaignSender->uploadTemplate();
    			$campaign->status = $campaign::STATUS_SENDING;
    			$campaign->sent_at = new \DateTime();
    			$logger->info('Template for campaign: '.$campaign->id.' '.$campaign->title.' uploaded, changing status to send.');
	   		} catch (\Exception $e) {
    			$campaign->status = $campaign::STATUS_ERROR;
    			$logger->err('Campaign: '.$campaign->id.' '.$campaign->title.' could not be prepared!');
    		}
			
    		$this->getEntityManager()->persist($campaign);
    		$this->getEntityManager()->flush();

    	}
		die('cron send');
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
