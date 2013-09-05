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

    	// Get large collection of all users related to list
    	$lc = new LargeCollection($this->getEntityManager());
    	$subscribersSlice = $lc->getSliceQuery($list->subsribers_connection, $limit = 30)->getResult();
    	//var_dump($subscribersSlice);

        return new ViewModel(array(
			'list' => $list,
        	'subscribers' => $subscribersSlice
        ));
    }

    public function editAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get lists repo
    	$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
    	$list = $lists->find($id);

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

    	$this->redirect()->toRoute('brands/show', array('id' => $brand_id));
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

    	$this->redirect()->toRoute('lists/show', array('id' => $connection->list->id));
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
