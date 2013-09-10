<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CampaignStats Service
 */
class CampaignStats implements ServiceLocatorAwareInterface {

	/**
	 * @var \Mandrill;
	 */
	protected $mandrill;

	/**
	 * Get campaign stats
	 * @param \Application\Entity\Campaign $campaign
	 */
	public function getStats(\Application\Entity\Campaign $campaign) {

		// TODO cache this in future

		$stats = array();

		$stats['info'] = $this->getMandrill()->tags->info(\Application\Service\CampaignSender::CAMPAIGN_TAG_PREFIX.$campaign->id);

		return $stats;

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