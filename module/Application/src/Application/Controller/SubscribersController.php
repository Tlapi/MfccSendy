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

class SubscribersController extends AbstractActionController
{
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
    public function indexAction()
    {
    	
    }
    
    public function showAction()
    {
    	$email = (string) $this->params()->fromRoute('email', 0);

    	$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
    	$subscriber = $subscribers->findOneBy(array('email' => $email));
    	
    	$log = $this->getEntityManager()->getRepository('Application\Entity\CampaignLog');
    	$usersLog = $log->getEventsForUser($email);
    	    	
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);
    	$viewModel->setVariables(array(
			'subscriber' => $subscriber,
			'log' => $usersLog,
    	));
    	
    	return $viewModel;
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
