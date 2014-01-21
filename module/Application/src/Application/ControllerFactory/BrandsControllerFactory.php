<?php
/* Application/src/Application/ControllerFactory/BrandsControllerFactory.php */
namespace Application\ControllerFactory;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

class BrandsControllerFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator) {
		/* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
		$sm   = $serviceLocator->getServiceLocator();
		$controller = new \Application\Controller\BrandsController();
		$controller->setMandrill($sm->get('mandrill'));
		return $controller;
	}
}