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
use DoctrineExtensions\LargeCollections\LargeCollection;
use Zend\Authentication\AuthenticationService;

class PublicController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {

        return new ViewModel(array(
        	
        ));
    }

    public function unsubscribeAction()
    {
    	$this->layout('layout/public');
    	
    	$id = $this->params()->fromRoute('id', 0);
    	$campaign_id = $this->params()->fromRoute('campaign_id', 0);
    	
    	$testing = false;
    	if(isset($_GET['testing'])){
    		$testing = true;
    	}
    	
    	// Get lists repo
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');
    	$connection = $listsToSubscribers->find($id);
    	
    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    	$campaign = $campaigns->find($campaign_id);
    	
    	// TODO this to factory
    	if($campaign->brand->locale){
    		$translator = $this->getServiceLocator()->get('translator');
    		$translator->setLocale($campaign->brand->locale);
    	}
    	
    	if(isset($_GET['confirm']) && !$testing){
    		// Set connection status
    		$connection->status = 0;
    		$this->getEntityManager()->persist($connection);
    		
    		// Log event
    		$logRow = new \Application\Entity\CampaignLog();
    		$logRow->event = 'unsub';
    		$logRow->email = $connection->subscriber->email;
    		$logRow->campaign = $campaign;
    		$this->getEntityManager()->persist($logRow);
    		
    		$this->getEntityManager()->flush();
    		
    		$this->flashMessenger()->addMessage($this->getServiceLocator()->get('translator')->translate('You have been unsubscribed!'));
    		if($connection->list->unsub_page)
    			return $this->plugin('redirect')->toUrl($connection->list->unsub_page);
    		else
    			$this->redirect()->toRoute('public/resubscribe', array('id' => $id));
    	}
    	
        return new ViewModel(array(
        	'id' => $id,
        	'campaign_id' => $campaign_id,
        	'testing' => $testing
        ));
    }
    
    public function resubscribeAction()
    {
    	$this->layout('layout/public');
    	
    	$id = (int) $this->params()->fromRoute('id', 0);
    	
    	// Get lists repo
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');
    	$connection = $listsToSubscribers->find($id);
    	
    	// TODO this to factory
    	if($connection->list->brand->locale){
    		$translator = $this->getServiceLocator()->get('translator');
    		$translator->setLocale($connection->list->brand->locale);
    	}
    	if(isset($_GET['confirm'])){
    		$connection->status = 1;
    		$this->getEntityManager()->persist($connection);
    		$this->getEntityManager()->flush();
    		
    		$this->flashMessenger()->addMessage($this->getServiceLocator()->get('translator')->translate('You have been resubscribed!'));
    		
    		$this->redirect()->toRoute('public/unsubscribe', array('id' => $id));
    	}
    	
        return new ViewModel(array(
        	'id' => $id
        ));
    }
    
    public function loginAction()
    {    	
    	$this->layout()->setVariable('publicReport', true);
    	
    	$id = (int) $this->params()->fromRoute('id', 0);
    	
    	if(isset($_POST['credential'])){
    		/*
    		$reports = $this->getEntityManager()->getRepository('Application\Entity\Report');
    		$report = $reports->findOneBy(array(
    			'password' => md5($_POST['credential']),
    			'campaign' => $id,
    		));
    		if($report){
    			
    			
    			// Attempt authentication, saving the result
    			$result = $auth->authenticate($authAdapter);
    			
    			$adapter = $this->zfcUserAuthentication()->getAuthAdapter();
    			$result = $adapter->prepareForAuthentication($this->getRequest());
    			$auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
    			var_dump($auth->isValid());
    		}*/
    		
    		$auth = new AuthenticationService();

    		// Use 'someNamespace' instead of 'Zend_Auth'
    		$auth->setStorage(new \Zend\Authentication\Storage\Session('hostLogin'));
    		
    		// Set up the authentication adapter
    		$authAdapter = new \Application\Authentication\Adapter\Host($_POST['credential'], $id, $this->getEntityManager());
    		
    		// Attempt authentication, saving the result
    		$result = $auth->authenticate($authAdapter);
    		
    		var_dump($auth->hasIdentity());
    		
    		if (!$result->isValid()) {
    			// Authentication failed; print the reasons why
    			foreach ($result->getMessages() as $message) {
    				echo "$message\n";
    			}
    			echo 'INVALID';
    		} else {
    			$this->redirect()->toRoute('public/report', array('id' => $id));
    		}
    	}
    }
    
    public function logoutAction()
    {
    	$auth = new AuthenticationService();
    	
    	$id = (int) $this->params()->fromRoute('id', 0);
    	
    	// Use 'someNamespace' instead of 'Zend_Auth'
    	$auth->setStorage(new \Zend\Authentication\Storage\Session('hostLogin'));
    	$auth->clearIdentity();
    	
    	// Redirect
    	$this->redirect()->toRoute('public/report/login', array('id' => $id));
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
