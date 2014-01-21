<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractRestfulController
{
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	private $apiKey = '1EF256EDF6AA24F9A88711525D574';
	
	protected function methodNotAllowed()
	{
		$this->response->setStatusCode(405);
		throw new \Exception('Method Not Allowed');
	}
	
	public function getList()
	{
		return $this->methodNotAllowed();
	}
	
	public function create($data)
	{   
		if($data['key']!=$this->apiKey){
			$response = $this->getResponse();
			$response->setStatusCode(403);
			return new JsonModel(array('error' => 'wrong api key'));
		}
		
		if(!isset($data['list_id']) || !isset($data['email'])){
			return new JsonModel(array('error' => 'missing list_id or email parameters'));
		}
		
		$lists = $this->getEntityManager()->getRepository('Application\Entity\Lists');
		$list = $lists->find($data['list_id']);
		
		if(!$list){
			return new JsonModel(array('error' => 'list not found'));
		}
		
		// Action used for POST requests
		$subscriber = new \Application\Entity\Subscriber();
		$subscriber->email = $data['email'];
		
		$result = $this->getServiceLocator()->get('listsService')->addSubscriber($subscriber, $list);
		
		return new JsonModel(array('result' => $result));
	}
	
	public function update($id, $data)
	{
		/*$apiService = $this->getServiceLocator()->get('apiService');
		
		$result = $apiService->update($id, $data);*/
		
		/*$listsToSubscribers = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\ListsToSubscribers');
		$status = $listsToSubscribers->findOneBy(array('list' => $this->params()->fromRoute('list_id', 0), 'subscriber' => $id));
		$status*/
		
		$response = $this->getResponse();
		$response->setStatusCode(200);
		
		return new JsonModel(array());
	}
    
	public function get($id)
	{
		/*$apiService = $this->getServiceLocator()->get('apiService');
		
		$result = $apiService->get($id);*/
		
		if($this->params()->fromRoute('key', 0)!=$this->apiKey){
			$response = $this->getResponse();
			$response->setStatusCode(403);
			return new JsonModel(array('error' => 'wrong api key'));
		}
		
		$listsToSubscribers = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\ListsToSubscribers');
		$status = $listsToSubscribers->findOneBy(array('list' => $this->params()->fromRoute('list_id', 0), 'subscriber' => $id));
		
		$response = $this->getResponse();
		$response->setStatusCode(200);
		
		return new JsonModel(array(
			'email' => $status->subscriber->email,
			'status' => $status->status
		));
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
    
}
