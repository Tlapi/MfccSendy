<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Webhooks Service
 */
class Webhooks implements ServiceLocatorAwareInterface {

	/**
	 * @var \Mandrill;
	 */
	protected $mandrill;

	public $webhooksList;

	/**
	 * Check current application webhook status
	 */
	public function checkWebhookStatus()
	{
		// TODO check loalhost
		foreach($this->webhooksList as $hook){
			if($this->getWebhookUrl() == $hook['url']){
				return true;
			}
		}
		return false;
	}

	/**
	 * Get current installation webhook url
	 */
	public function getWebhookUrl()
	{
		// TODO check loalhost
		return 'http://'.$_SERVER['HTTP_HOST'].$this->getServiceLocator()->get('viewHelperManager')->get('url')->__invoke('hooks');
	}

	/**
	 * Try to add webhook to mandrill
	 */
	public function addWebhook()
	{
		$this->getMandrill()->webhooks->add($this->getWebhookUrl(), 'Mailing Machinegun Webhook', array(
			//'send',
			'hard_bounce',
			'soft_bounce',
			'open',
			'click',
			'spam',
			//'unsub',
			//'reject',
		));
	}

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/* Mandrill setter and getter */

	public function setMandrill(\Mandrill $mandrill) {
		$this->mandrill = $mandrill;
		$this->webhooksList = $this->mandrill->webhooks->getList();
	}

	public function getMandrill() {
		return $this->mandrill;
	}

}