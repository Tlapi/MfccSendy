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

class ListsController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * On controller dispatch
	 */
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$this->layout()->setVariable('active', 'brands');
		$this->layout()->setVariable('menu', 'sidebar-brands');
		return parent::onDispatch( $e );
	}

    public function indexAction()
    {
    	// Get brands repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');

        return new ViewModel(array(
        	'lists' => $lists->findAll()
        ));
    }

    public function showAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
		// Get list
    	$list = $lists->find($id);

    	$this->layout()->setVariable('brand_id', $list->brand->id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	// Get large collection of all users related to list
    	$lc = new LargeCollection($this->getEntityManager());
    	$subscribersSlice = $lc->getSliceQuery($list->subsribers_connection, $limit = 30)->getResult();
    	//var_dump($subscribersSlice);

    	// Stats
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');
    	// TODO - too slow 
    	//$totalYearStats = $listsToSubscribers->getTotalForLastYear($list->id);

        return new ViewModel(array(
			'list' => $list,
        	'subscribers' => $subscribersSlice,
        	'totalYearStats' => $totalYearStats,
        ));
    }

    public function editAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$list = $lists->find($id);

    	$this->layout()->setVariable('brand_id', $list->brand->id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	$form = new \Application\Form\Lists();
    	$form->prepareElements();
    	$form->bind($list);
    	$form->setInputFilter(new \Application\Form\ListsFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {
    			$this->getEntityManager()->persist($list);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('Changes has been saved!');

    			$this->redirect()->toRoute('brands/show', array('id' => $list->brand->id));
    		}
    	}

        return new ViewModel(array(
        		'form' => $form
        ));
    }

    public function addAction()
    {
    	$brand_id = (int) $this->params()->fromRoute('brand_id', 0);

    	$this->layout()->setVariable('brand_id', $brand_id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$form = new \Application\Form\Lists();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\ListsFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$newList = new \Application\Entity\Lists();
    		$form->bind($newList);
    		$data = $request->getPost();

    		// set brand
    		$newList->brand = $brands->find($brand_id);

    		$form->setData($data);
    		if ($form->isValid()) {
    			$this->getEntityManager()->persist($newList);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('New list has been added!');

    			$this->redirect()->toRoute('brands/show', array('id' => $brand_id));
    		}
    	}

    	return new ViewModel(array(
    			'form' => $form
    	));
    }

    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');

    	$list = $lists->find($id);

    	$brand_id = $list->brand->id;

    	$this->getEntityManager()->remove($list);
    	$this->getEntityManager()->flush();

    	$this->flashMessenger()->addMessage('List has been deleted!');

    	$this->redirect()->toRoute('brands/show', array('id' => $brand_id));
    }

    /**
     * Split lists action
     */
    public function splitAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$list = $lists->find($id);

    	$form = new \Application\Form\ListSplit();
    	$form->prepareElements();

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();
    		$form->setData($data);
    		if ($form->isValid()) {
    			$brand_id = $list->brand->id;
    			for($iteration = 1;$iteration <= count($list->subsribers_connection) / $data['chunk_size'];$iteration++){
	    			$newList = new \Application\Entity\Lists();
	    			$newList->name = $list->name.' - '.$iteration;
	    			$newList->brand = $list->brand;
	    			$this->getEntityManager()->persist($newList);
	    			$this->getEntityManager()->flush();
	    			$this->getEntityManager()->getConnection()->executeUpdate(
						"UPDATE lists_to_subscribers SET list_id = $newList->id
				     	WHERE list_id = $id
				     	LIMIT ".$data['chunk_size']
    				);
    			}
    			$this->getEntityManager()->remove($list);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('List has been split into '.$iteration.' chunks!');

    			$this->redirect()->toRoute('brands/show', array('id' => $brand_id));
    		}
    	}

    	$this->layout()->setVariable('brand_id', $list->brand->id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	return new ViewModel(array(
    			'form' => $form,
    	));
    }

    /**
     * Merge lists action
     */
    public function mergeAction()
    {
    	$id = (int) $this->params()->fromRoute('brand_id', 0);

    	$this->layout()->setVariable('brand_id', $id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	$form = new \Application\Form\ListsMerge();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\ListsMergeFilter());

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');
    	$brand = $brands->find($id);
    	$options = array();
    	foreach($brand->lists as $list){
    		$options[$list->id] = $list->name;
    	}
    	$form->add(array(
	             'type' => 'Zend\Form\Element\MultiCheckbox',
	             'name' => 'merge',
	             'options' => array(
	                     'label' => 'Lists to merge',
	                     'value_options' => $options
	             )
	    ));
    	$form->add(array(
	             'type' => 'Zend\Form\Element\Select',
	             'name' => 'merge_into',
	             'options' => array(
	                     'label' => 'Merge into',
	                     'value_options' => $options
	             )
	    ));

    	// Process form
    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();
    		$form->setData($data);
    		if ($form->isValid()) {
    			$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    			$mergeTo = $lists->find($data['merge_into']);
				// Merge lists
				foreach($data['merge'] as $list_id){
					$list = $lists->find($list_id);
					//$list->merged_into_list = $mergeTo;
					$this->getEntityManager()->remove($list);
					$q = $this->getEntityManager()->createQuery('update Application\Entity\ListsToSubscribers m set m.list_id = '.$data['merge_into'].' WHERE m.list_id = '.$list_id);
					$numUpdated = $q->execute();
				}
				$this->getEntityManager()->flush();

				$this->flashMessenger()->addMessage('Lists has been merged!');

				$this->redirect()->toRoute('brands/show', array('id' => $id));
    		}
    	}

    	return new ViewModel(array(
			'form' => $form,
    	));
    }

    public function deleteUserAction()
    {
    	$connection_id = (int) $this->params()->fromRoute('connection_id', 0);

    	// Get lists repo
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');

    	$connection = $listsToSubscribers->find($connection_id);

    	$list_id = $connection->list->id;

    	$this->getEntityManager()->remove($connection);
    	$this->getEntityManager()->flush();

    	$this->flashMessenger()->addMessage('User has been deleted!');

    	$this->redirect()->toRoute('lists/show', array('id' => $list_id));
    }

    public function resubscribeAction()
    {
    	$connection_id = (int) $this->params()->fromRoute('connection_id', 0);

    	// Get lists repo
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');

    	$connection = $listsToSubscribers->find($connection_id);

    	$connection->status = 1;

    	$this->getEntityManager()->persist($connection);
    	$this->getEntityManager()->flush();

    	$this->flashMessenger()->addMessage('User has been resubscribed!');

    	$this->redirect()->toRoute('lists/show', array('id' => $connection->list->id));
    }

    public function unsubscribeAction()
    {
    	$connection_id = (int) $this->params()->fromRoute('connection_id', 0);

    	// Get lists repo
    	$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');

    	$connection = $listsToSubscribers->find($connection_id);

    	$connection->status = 2;

    	$this->getEntityManager()->persist($connection);
    	$this->getEntityManager()->flush();

    	$this->flashMessenger()->addMessage('User has been unsubscribed!');

    	$this->redirect()->toRoute('lists/show', array('id' => $connection->list->id));
    }

    public function addSubscribersAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$list = $lists->find($id);

    	$this->layout()->setVariable('brand_id', $list->brand->id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	$request = $this->getRequest();
    	if ($request->isPost()){
			// TODO Move to form and validate before processing
    		$data = $request->getPost();
    		$this->getServiceLocator()->get('listsService')->importSubsribersFromString($list, $data['line']);

    		$this->flashMessenger()->addMessage('Subscribers has been added!');

    		$this->redirect()->toRoute('lists/show', array('id' => $list->id));
    	}
    }

    public function removeSubscribersAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$list = $lists->find($id);

    	$this->layout()->setVariable('brand_id', $list->brand->id);
    	$this->layout()->setVariable('submenu_active', 'lists');

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		// TODO Move to form and validate before processing
    		$data = $request->getPost();
    		$this->getServiceLocator()->get('listsService')->removeSubsribersFromString($list, $data['line']);

    		$this->flashMessenger()->addMessage('Subscribers has been removed!');

    		$this->redirect()->toRoute('lists/show', array('id' => $list->id));
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
