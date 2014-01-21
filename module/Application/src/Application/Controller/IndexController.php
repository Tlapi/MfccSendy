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

class IndexController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {
	    if (!$this->isAllowed('alcohol', 'consume')) {
	        throw new \BjyAuthorize\Exception\UnAuthorizedException('Grow a beard first!');
	    }
    	// TODO move to factory
    	$mandrill = $this->getServiceLocator()->get('mandrill');
    	$webhooks = $this->getServiceLocator()->get('webhooks');

    	$webhookStatus = $webhooks->checkWebhookStatus();

    	$mandrillInfo = $mandrill->users->info();
    	$this->layout()->setVariable('mandrillInfo', $mandrillInfo);

    	// Subscribers
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');
    	$totalYearStats = $listsToSubscribers->getTotalForLastYear();
    	$unsubYearStats = $listsToSubscribers->getUnsubsForLastYear();
    	$complaintsYearStats = $listsToSubscribers->getComplaintsForLastYear();

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	// main menu
    	$this->layout()->setVariable('active', 'dashboard');
    	
    	// sending volume
    	$sendingVolume = array();
    	try {
    		//$mandrill->tags->timeSeries(\Application\Service\CampaignSender::APP_TAG);
    		$sendingVolume = $mandrill->tags->allTimeSeries();
    	} catch (\Mandrill_Invalid_Tag_Name $e) {
    		
    	}

        return new ViewModel(array(
        	'mandrillInfo' => $mandrillInfo,
        	'webhookStatus' => $webhookStatus,
        	'totalYearStats' => $totalYearStats,
        	'unsubYearStats' => $unsubYearStats,
        	'complaintsYearStats' => $complaintsYearStats,
        	'brands' => $brands->findAll(),
        	'sendingVolume' => $sendingVolume,
        	'subaccounts' => $mandrill->subaccounts->getList()
        ));
    }

    public function statsAction()
    {
    	// TODO move to factory
    	$mandrill = $this->getServiceLocator()->get('mandrill');
    	//$sendgrid = $this->getServiceLocator()->get('sendgrid');

    	$mandrillInfo = $mandrill->users->info();
    	$this->layout()->setVariable('mandrillInfo', $mandrillInfo);

    	// main menu
    	$this->layout()->setVariable('active', 'dashboard');

    	//var_dump($sendgrid->profile_get());

        return new ViewModel(array(
        	'mandrillInfo' => $mandrillInfo
        ));
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
