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
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

use DOMPDFModule\View\Model\PdfModel;

class CampaignsController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Application\Entity\Brand;
	 */
	protected $brand;
	
	/**
	 * @var \Application\Entity\Campaign;
	 */
	protected $campaign;
		
	/**
	 * @var \Application\Entity\Campaign;
	 */
	protected $logService;
	
	
    /**
     * On controller dispatch
     */
    public function onDispatch( \Zend\Mvc\MvcEvent $e )
    {
    	// Layout settings
    	$this->layout()->setVariable('active', 'brands');
    	$this->layout()->setVariable('menu', 'sidebar-brands');
    	
    	// Public report ?
    	$this->layout()->setVariable('publicReport', $this->params()->fromRoute('publicReport', false));
    	if($this->params()->fromRoute('publicReport')){
    		$auth = new \Zend\Authentication\AuthenticationService();
    		
    		// Use 'someNamespace' instead of 'Zend_Auth'
    		$auth->setStorage(new \Zend\Authentication\Storage\Session('hostLogin'));
    		
    		if(!$auth->hasIdentity()){
    			$this->redirect()->toRoute('public/report/login', array('id' => $this->params()->fromRoute('id')));
    		}
    		
    		$this->layout()->setVariable('campaign_id', $this->params()->fromRoute('id'));
    	}
    	
    	// Get and set campaign
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if($id){
    		// Set campaign
    		$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    		$this->campaign = $campaigns->find($id);
    		$this->layout()->setVariable('brand_id', $this->campaign->brand->id);
    		
    		// Set campaign log service
    		$this->logService = $this->getServiceLocator()->get('campaignLog');
    		$this->logService->setCampaign($this->campaign);
    	}
    	
    	return parent::onDispatch( $e );
    }

    /**
     * Show campaign report
     */
    public function showAction()
    {
    	$campaignStats = $this->getServiceLocator()->get('campaignStats');
    	$stats = $campaignStats->getStats($this->campaign);
    	
    	// Get log repository
    	$log = $this->getEntityManager()->getRepository('Application\Entity\CampaignLog');
    	
    	// Get latest events
    	$lastClicked = $this->logService->getLastClicked();
    	$lastOpened = $this->logService->getLastOpened();
    	
    	// Count unsubscribed
    	$unsubCount = $this->logService->countUnsubscribedEvents();
    	
    	// Get most opens subscribers
    	$mostOpens = $this->logService->getSubscribersWithMostOpens();
    	
    	// Get top clicked links
    	$topClicked = $this->logService->getTopClickedLinks();
    	
    	// Get campaign lists
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$campaignLists = $lists->findByIds($this->campaign->recepient_lists);
    	
    	// Get 24 hour performance
    	$lastDayClicks = $this->logService->get24HourClickStats();
    	$lastDayOpens = $this->logService->get24HourOpenStats();

        return new ViewModel(array(
			'campaign' => $this->campaign,
			'stats' => $stats,
			'lastOpened' => $lastOpened,
			'lastClicked' => $lastClicked,
			'unsubCount' => $unsubCount,
			'campaignLists' => $campaignLists,
			'mostOpens' => $mostOpens,
			'topClicked' => $topClicked,
			'lastDayClicks' => $lastDayClicks,
			'lastDayOpens' => $lastDayOpens,
        ));
    }
    
    /**
     * Show OS & Clients report
     */
    public function osAction()
    {
    	// OS
    	$os = $this->campaign->getFormatedOsStats();
		
		// CLIENTS
    	$clients = $this->campaign->getFormatedClientsStats();
    
    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'os' => $os,
    			'clients' => $clients,
    			'active' => 'os'
    	));
    }
    
    /**
     * Show demographics report
     */
	public function demographicsAction()
    {
    	// Locations
    	$locations = $this->campaign->getFormatedLocationStats();
    	
    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'locations' => $locations,
    			'active' => 'demographics',
    	));
    }
    
    /**
     * Show links report
     */
    public function linksAction()
    {
    	// Get log repository
    	$log = $this->getEntityManager()->getRepository('Application\Entity\CampaignLog');
    	$logList = $log->getClickStatsCampaign($this->campaign->id, 'click');
    	
    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'active' => 'links',
    			'log' => $logList,
    			'publicReport' => $this->params()->fromRoute('publicReport', false),
    	));
    }
    
    /**
     * Share this report to public
     */
    public function shareAction()
    {
    	$form = new \Application\Form\ShareReport();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\ShareReportFilter());
    	
    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$newReport = new \Application\Entity\Report();
    		$form->bind($newReport);
    		$data = $request->getPost();
    		
    		// set campaign
    		$newReport->campaign = $this->campaign;
    		
    		$form->setData($data);
    		if ($form->isValid()) {
    			$this->getEntityManager()->persist($newReport);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('Report has been shared!');
    			
    			// Send mail
    			$plugins = $this->getServiceLocator()->get('ViewHelperManager');
    			$href = $plugins->get('url')->__invoke('public/report', array('id' => $this->campaign->id), array('force_canonical' => true));
    			
    			$mandrill = $this->getServiceLocator()->get('mandrill');
    			$mandrill->messages->send(array(
    				'html' => 'You have been shared a new report baby!<br /><a href="'.$href.'">Click here</a><br />Your password: '.$data['password'],
    				'subject' => 'New report from machinegun',
    				'from_email' => 'machinegun@mfcc.cz',
    				'to' => array(array('email' => $data['recipient']))
    			));

    			$this->redirect()->toRoute('campaigns/show/share', array('id' => $this->campaign->id));
    		}
    	}
    	
    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'form' => $form,
    	));
    }
    
    /**
     * Show user activities report
     */
    public function activityAction()
    {
    	$filter = (string) $this->params()->fromRoute('filter', 0);
    	
    	// TODO if not filter set
    	
    	// Get log repository
    	$log = $this->getEntityManager()->getRepository('Application\Entity\CampaignLog');
    	
    	if($filter=='sent'){
    		$query = $this->logService->getEvents('open');
    		$action = 'Opens';
    	}
    	if($filter=='bounced'){
    		$query = $this->logService->getEvents(array('hard_bounce', 'soft_bounce'));
    		$action = 'Bounced';
    	}
    	if($filter=='opened'){
    		$query = $this->logService->getEvents('open');
    		$action = 'Opens';
    	}
    	if($filter=='clicked'){
    		$query = $this->logService->getEvents('click');
    		$action = 'Clicks';
    	}
    	if($filter=='unsubscribed'){
    		$query = $this->logService->getEvents('unsub');
    		$action = 'Unsubscribed';
    	}
    	if($filter=='complained'){
    		$query = $this->logService->getEvents('spam');
    		$action = 'Complained';
    	}
    	
    	// Set paginator
    	$paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
    	$paginator->setDefaultItemCountPerPage(10);
    	
    	// Set page
    	$page = (int)$this->params()->fromQuery('page');
    	if($page) $paginator->setCurrentPageNumber($page);
    
    	$this->layout()->setVariable('brand_id', $this->campaign->brand->id);
    
    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'log' => $paginator,
    			'action' => $action,
    			'filter' => $filter,
    			'active' => 'activity'
    	));
    }

    /**
     * Render campaign template
     */
    public function renderAction()
    {
    	$clickmap = (bool) $this->params()->fromRoute('clickmap', false);
    	
    	$logList = null;
    	
    	// Get log repository & Generate clickmap
    	if($clickmap){
    		$logList = $this->logService->getClickStats();
    	}

    	$viewModel = new ViewModel(array(
			'campaign' => $this->campaign,
			'log' => $logList,
        ));
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
    
    /**
     * Get PDF report
     */
    public function renderPdfAction()
    {
    	$pdf = new PdfModel();
    	$pdf->setOption('filename', 'campaign-report'); // Triggers PDF download, automatically appends ".pdf"
    	$pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
    	$pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"
    	
    	$campaignStats = $this->getServiceLocator()->get('campaignStats');
    	$stats = $campaignStats->getStats($this->campaign);
    	 
    	// Get most opens subscribers
    	$mostOpens = $this->logService->getSubscribersWithMostOpens();
    	 
    	// Get top clicked links
    	$topClicked = $this->logService->getTopClickedLinks();
    	
    	// Get links stats
    	$links = $this->logService->getClickStats();
    	
    	// Locations
    	$locations = $this->campaign->getFormatedLocationStats();
    	
    	// OS
    	$os = $this->campaign->getFormatedOsStats();
		
		// CLIENTS
    	$clients = $this->campaign->getFormatedClientsStats();
    	
    	// Count unsubscribed
    	$unsubCount = $this->logService->countUnsubscribedEvents();
    	
    	// To set view variables
    	$pdf->setVariables(array(
    			'campaign' => $this->campaign,
    			'stats' => $stats,
    			'mostOpens' => $mostOpens,
    			'topClicked' => $topClicked,
    			'links' => $links,
    			'locations' => $locations['list'],
    			'os' => $os['list'],
    			'clients' => $clients['list'],
    			'unsubCount' => $unsubCount,
    	));
    	
    	return $pdf;
    }

    /**
     * Create new campaign
     * TODO move?
     */
    public function addAction()
    {
    	$brand_id = (int) $this->params()->fromRoute('brand_id', 0);
    	$this->layout()->setVariable('brand_id', $brand_id);
    	$this->layout()->setVariable('submenu_active', 'create_campaign');

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');
    	$brand = $brands->find($brand_id);

    	$form = new \Application\Form\Campaign();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\CampaignFilter());

    	// Set defaults from brand
    	$form->get('from_name')->setValue($brand->from_name);
    	$form->get('from_email')->setValue($brand->from_email);
    	$form->get('reply_to')->setValue($brand->reply_to);
    	
    	// get id if we edit campaign
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if($id){
    		$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    		$campaign = $campaigns->find($id);
    		$form->bind($campaign);
    	}
    	
    	$request = $this->getRequest();
    	if ($request->isPost()){
    		if($id) {
    			$newCampaign = $campaign;
    			$brand = $campaign->brand;
    		} else {
    			$newCampaign = new \Application\Entity\Campaign();
    			$form->bind($newCampaign);
    		}
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {

    			$newCampaign->brand = $brand;
    			$newCampaign->status = 0;
    			$newCampaign->last_sent_id = 0;

    			$this->getEntityManager()->persist($newCampaign);
    			$this->getEntityManager()->flush();

    			$this->redirect()->toRoute('campaigns/send-to', array('id' => $newCampaign->id));
    		}
    	}

    	return new ViewModel(array(
    			'form' => $form
    	));
    }

    /**
     * Send to step in Campaign creation
     */
    public function sendToAction()
    {
    	$this->layout()->setVariable('submenu_active', 'create_campaign');

    	$form = new \Application\Form\CampaignRecipients();
    	$form->prepareElements($this->campaign);
    	$form->setInputFilter(new \Application\Form\CampaignRecipientsFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {

    			$this->campaign->recepient_lists = $data['recipients'];
    			$this->campaign->status = \Application\Entity\Campaign::STATUS_PREPARING;

    			$this->getEntityManager()->persist($this->campaign);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('Campaign is preparing to be sent!');

    			$this->redirect()->toRoute('brands/show', array('id' => $this->campaign->brand->id));
    		}
    	}

    	return new ViewModel(array(
    			'campaign' => $this->campaign,
    			'form' => $form,
    	));
    }

    /**
     * Get number of campaign recipients
     */
    public function calculateRecipientsAction()
    {

    	// Move this to service
    	$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
    	$lists = implode(',', $_POST['lists']);

    	try {
	    	$targets = $this->getEntityManager()->createQuery("SELECT COUNT(s)
	    			FROM Application\Entity\Subscriber AS s
	    			LEFT JOIN Application\Entity\ListsToSubscribers AS ls
	    			WITH s.id = ls.subscriber
	    			WHERE ls.list in ($lists)
	    			AND ls.status = 1
	    			GROUP BY s.email
	    			ORDER BY s.id ASC
	    			")->getResult();

	    	echo count($targets);

    	} catch (\Doctrine\ORM\NoResultException $e){
    		echo '0';
    	}

    	exit();
    }

    /**
     * Send test e-mails
     */
    public function sendTestAction()
    {

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();
    		if(isset($data['test_email'])){
    			$emails = explode(',', $data['test_email']);
    			// TODO set Di
    			$campaignSender = $this->getServiceLocator()->get('campaignSender');
    			$campaignSender->setCampaign($this->campaign);
    			$campaignSender->uploadTemplate();
    			$campaignSender->sendTest($emails);
    		}
    	}

    	$result = new JsonModel(array(
    		'success' => true,
    		'emails' => $emails
    	));

    	return $result;
    }

    /**
     * Duplicate campaign
     */
    public function duplicateAction()
    {

    	$newCampaign = new \Application\Entity\Campaign();
    	// fix html
    	//$campaign->html_text =  
    	$newCampaign->exchangeArray($this->campaign->getArrayCopy());
    	$newCampaign->last_sent_id = 0;
    	$newCampaign->status = \Application\Entity\Campaign::STATUS_DRAFT;
    	$newCampaign->brand = $this->campaign->brand;

    	$this->getEntityManager()->persist($newCampaign);
    	$this->getEntityManager()->flush(); // error with missing id ???

    	$this->flashMessenger()->addMessage('Campaign has been duplicated!');
    	
    	$this->redirect()->toRoute('brands/show', array('id' => $newCampaign->brand->id));
    }

    /**
     * Delete campaign
     */
    public function deleteAction()
    {
    	$brand_id = $this->campaign->brand->id;

    	$this->getEntityManager()->remove($this->campaign);
    	$this->getEntityManager()->flush();
    	
    	$this->flashMessenger()->addMessage('Campaign has been deleted!');

    	$this->redirect()->toRoute('brands/show', array('id' => $brand_id));
    }

    /**
     * Entity Manager
     */
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

    /**
     * @return the $brand
     */
    public function getBrand() {
    	return $this->brand;
    }

    /**
     * @param Brand; $brand
     */
    public function setBrand($brand) {
    	$this->brand = $brand;
    }

}
