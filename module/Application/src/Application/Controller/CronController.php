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

class CronController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {

    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');

    	// Get ONE active campaign and send / or continue sending
    	$campaign = $campaigns->findOneBy(array('status' => \Application\Entity\Campaign::STATUS_SENDING));

    	// TODO set Di
    	$campaignSender = $this->getServiceLocator()->get('campaignSender');
    	$campaignSender->setCampaign($campaign);
    	$campaignSender->sendCampaign();

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
