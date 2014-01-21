<?php
/* Application/src/Application/ControllerFactory/CampaignsControllerFactory.php */
namespace Application\ControllerFactory;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

class CampaignsControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator) {
		/* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
		$sm   = $serviceLocator->getServiceLocator();
		$controller = new \Application\Controller\CampaignsController();

		//$events = $serviceLocator->get('Application')->getEventManager();
		//$events->attach(MvcEvent::EVENT_RENDER, array($controller, 'setVariableToLayout'), 100);
		
		return $controller;
	}
}