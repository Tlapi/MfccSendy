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

    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    	/*
    	// Get ONE preapring campaing and upload the template to mandrill
    	$campaign = $campaigns->findOneBy(array('status' => \Application\Entity\Campaign::STATUS_PREPARING));

    	if($campaign!=null)
    	{	$campaignSender = $this->getServiceLocator()->get('campaignSender');
    		$campaignSender->setCampaign($campaign);

    		try {
    			$campaignSender->uploadTemplate();
    			$campaign->status = $campaign::STATUS_SENDING;
    			$this->getEntityManager()->persist($campaign);
    			$this->getEntityManager()->flush();
	   		} catch (Exception $e) {
    			$campaign->status = $campaign::STATUS_ERROR;
    			$this->getEntityManager()->persist($campaign);
    			$this->getEntityManager()->flush();
    		}



    	}

    	// Get ONE active campaign and send / or continue sending
    	$campaign = $campaigns->findOneBy(array('status' => \Application\Entity\Campaign::STATUS_SENDING));

    	if($campaign!=null)
    	{	$campaignSender = $this->getServiceLocator()->get('campaignSender');
    		$campaignSender->setCampaign($campaign);
	    	// TODO set Di
    		$campaignSender->sendCampaign();

    	}*/
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
