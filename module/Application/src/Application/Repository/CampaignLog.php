<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class CampaignLog extends EntityRepository
{

	/**
	 * Get last clicked event for provided campaign
	 */
	public function getLastClickedForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
		->andWhere('p.campaign = :c_id')
		->andWhere('p.event = :event')
		->orderBy('p.occured_at', 'DESC')
		->setMaxResults(1);
		
		$qb->setParameter('c_id', $campaign_id)->setParameter('event', 'click');
		
		return $qb->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * Get last opened event for provided campaign
	 */
	public function getLastOpenedForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
		->andWhere('p.campaign = :c_id')
		->andWhere('p.event = :event')
		->orderBy('p.occured_at', 'DESC')
		->setMaxResults(1);
		
		$qb->setParameter('c_id', $campaign_id)->setParameter('event', 'open');
		
		return $qb->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * Count all unsubscribed events for provided campaign
	 */
	public function countUnsubscribedEventsForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
		->andWhere('p.campaign = :c_id')
		->andWhere('p.event = :event');
		
		$qb->setParameter('c_id', $campaign_id)->setParameter('event', 'unsub');
		
		$total = $qb->select('COUNT(p)')
			->getQuery()
			->getSingleScalarResult();
		
		return $total;
	}
	
	/**
	 * get subscribers with most opens
	 */
	public function getSubscribersWithMostOpensForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.email) AS mycount')
		->andWhere('p.campaign = :c_id')
		->andWhere('p.event = :event');
		
		$qb->setParameter('c_id', $campaign_id)->setParameter('event', 'open');
		
		$result = $qb->orderBy('mycount', 'DESC')->addGroupBy('p.email')->setMaxResults(10)
			->getQuery()
			->getResult();
		
		return $result;
	}
	
	/**
	 * get top clicked links
	 */
	public function getTopClickedLinksForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.msg) AS mycount')
		->andWhere('p.campaign = :c_id')
		->andWhere('p.event = :event');
		
		$qb->setParameter('c_id', $campaign_id)->setParameter('event', 'click');
		
		$result = $qb->orderBy('mycount', 'DESC')->addGroupBy('p.msg')->setMaxResults(10)
			->getQuery()
			->getResult();
		
		return $result;
	}
	
	/**
	 * get click stats
	 */
	public function getClickStatsCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.id) AS total')
		->andWhere('p.campaign = :c_id')
		->andWhere("p.msg NOT LIKE 'http://machinegun.mfcc.cz/public/unsubscribe/%'");
		
		$qb->andWhere('p.event = :event');
		$qb->setParameter('event', 'click');
		
		$qb->setParameter('c_id', $campaign_id);
		
		
		$result = $qb->orderBy('total', 'DESC')->addGroupBy('p.msg')
			->getQuery()
			->getResult();
		
		return $result;
	}
	
	/**
	 * get 24 hour click stats
	 */
	public function get24HourClickStatsForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.id) AS total, SUBSTRING(p.occured_at, 12, 2) AS hour, SUBSTRING(p.occured_at, 9, 2) AS day')
		->andWhere('p.campaign = :c_id')
		->andWhere("p.msg NOT LIKE 'http://machinegun.mfcc.cz/public/unsubscribe/%'");
		
		$qb->andWhere('p.event = :event');
		$qb->setParameter('event', 'click');
		
		$qb->andWhere('p.occured_at > :dayback');
		$qb->setParameter('dayback', date('Y-m-j G:i:s', strtotime('-1 day', strtotime(date('Y-m-j G:i:s')))));
		
		$qb->setParameter('c_id', $campaign_id);
		
		
		$result = $qb->orderBy('day', 'ASC')->addOrderBy('hour', 'ASC')->addGroupBy('hour')
			->getQuery()
			->getResult();
		
		return $result;
	}
	
	/**
	 * get 24 hour open stats
	 */
	public function get24HourOpenStatsForCampaign($campaign_id){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.id) AS total, SUBSTRING(p.occured_at, 12, 2) AS hour, SUBSTRING(p.occured_at, 9, 2) AS day')
		->andWhere('p.campaign = :c_id');
		
		$qb->andWhere('p.event = :event');
		$qb->setParameter('event', 'open');
		
		$qb->andWhere('p.occured_at > :dayback');
		$qb->setParameter('dayback', date('Y-m-j G:i:s', strtotime('-1 day', strtotime(date('Y-m-j G:i:s')))));
		
		$qb->setParameter('c_id', $campaign_id);
		
		
		$result = $qb->orderBy('day', 'ASC')->addOrderBy('hour', 'ASC')->addGroupBy('hour')
			->getQuery()
			->getResult();
		
		return $result;
	}
	
	/**
	 * get top clicked links
	 */
	public function getEventsForCampaign($campaign_id, $event){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p, COUNT(p.email) AS mycount')
		->andWhere('p.campaign = :c_id');
		
		if(is_array($event)){
			$qb->andWhere('p.event IN (:event)');
			$qb->setParameter('event', $event);
		} else {
			$qb->andWhere('p.event = :event');
			$qb->setParameter('event', $event);
		}
		
		$qb->setParameter('c_id', $campaign_id);
		
		
		$query = $qb->orderBy('p.occured_at', 'DESC')->addGroupBy('p.email')->setMaxResults(20)
			->getQuery();
		
		return $query;
	}
	
	/**
	 * Get events for provided email
	 */
	public function getEventsForUser($email){
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
		->andWhere('p.email = :mail');
		$qb->setParameter('mail', $email);
		
		
		$result = $qb->orderBy('p.occured_at', 'DESC')
		->getQuery()
		->getResult();
		
		return $result;
	}

}