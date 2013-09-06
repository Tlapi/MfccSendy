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
    	// TODO move to factory
    	$mandrill = $this->getServiceLocator()->get('mandrill');
    	$webhooks = $this->getServiceLocator()->get('webhooks');

    	$webhookStatus = $webhooks->checkWebhookStatus();

    	$mandrillInfo = $mandrill->users->info();

    	// Subscribers
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');
    	$totalYearStats = $listsToSubscribers->getTotalForLastYear();
    	$unsubYearStats = $listsToSubscribers->getUnsubsForLastYear();
    	$complaintsYearStats = $listsToSubscribers->getComplaintsForLastYear();

        return new ViewModel(array(
        	'mandrillInfo' => $mandrillInfo,
        	'webhookStatus' => $webhookStatus,
        	'totalYearStats' => $totalYearStats,
        	'unsubYearStats' => $unsubYearStats,
        	'complaintsYearStats' => $complaintsYearStats,
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
