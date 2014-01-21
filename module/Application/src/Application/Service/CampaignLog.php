<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * CampaignStats Service
 */
class CampaignLog implements ServiceLocatorAwareInterface {

	/**
	 * @var \Application\Entity\Campaign;
	 */
	protected $campaign;
	
	/**
	 * @var \Application\Repository\CampaignLog;
	 */
	protected $logRepository;

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
	
	/**
	 * Get last clicked event
	 */
	public function getLastClicked() {
		return $this->logRepository->getLastClickedForCampaign($this->campaign->id);
	}
	
	/**
	 * Get last opened event
	 */
	public function getLastOpened() {
		return $this->logRepository->getLastOpenedForCampaign($this->campaign->id);
	}
	
	/**
	 * Count unsubscribed events
	 */
	public function countUnsubscribedEvents() {
		return $this->logRepository->countUnsubscribedEventsForCampaign($this->campaign->id);
	}
	
	/**
	 * Get subscribers with most opens
	 */
	public function getSubscribersWithMostOpens() {
		return $this->logRepository->getSubscribersWithMostOpensForCampaign($this->campaign->id);
	}
	
	/**
	 * Get top clicked links
	 */
	public function getTopClickedLinks() {
		return $this->logRepository->getTopClickedLinksForCampaign($this->campaign->id);
	}
	
	/**
	 * Get events
	 */
	public function getEvents($event) {
		return $this->logRepository->getEventsForCampaign($this->campaign->id, $event);
	}
	
	/**
	 * Get click stats
	 */
	public function getClickStats() {
		return $this->logRepository->getClickStatsCampaign($this->campaign->id, 'click');
	}
	
	/**
	 * Get 24 hour click stats
	 */
	public function get24HourClickStats() {
		return $this->logRepository->get24HourClickStatsForCampaign($this->campaign->id);
	}
	
	/**
	 * Get 24 hour opens stats
	 */
	public function get24HourOpenStats() {
		return $this->logRepository->get24HourOpenStatsForCampaign($this->campaign->id);
	}

	/* Log repository setter and getter */

	public function setLogRepository(\Application\Repository\CampaignLog $logRepository) {
		$this->logRepository = $logRepository;
	}

	public function getLogRepository() {
		return $this->logRepository;
	}
	
	/* Campaign setter and getter */

	public function setCampaign(\Application\Entity\Campaign $campaign) {
		$this->campaign = $campaign;
	}

	public function getCampaign() {
		return $this->campaign;
	}

}