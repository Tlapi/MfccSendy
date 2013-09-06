<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class ListsToSubscribers extends EntityRepository
{

	/**
	 * Gets stats of total subscribers in last 12 months
	 * @param int $list_id
	 * @return multitype:number
	 */
	public function getTotalForLastYear($list_id = null)
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qb = $this->createQueryBuilder('p');
	    $qb->select('p')
	       ->andWhere('YEAR(p.subscribed_at) = :year')
	       ->andWhere('MONTH(p.subscribed_at) = :month');

	    if($list_id){
	    	$qb->andWhere('p.list = :list_id');
	    	$qb->setParameter('list_id', $list_id);
	    }

	    $stats = array();
		for ($i = 11; $i >= 0; $i--) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$monthPrint = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
			$year = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
			$yearPrint = date("y", strtotime( date( 'Y-m-01' )." -$i months"));

			$qb->setParameter('year', $year)->setParameter('month', $month);

			$stats['data'][] = count($qb->getQuery()->getResult());
			$stats['print'][] = "'".$monthPrint." ".$yearPrint."'";
		}

		return $stats;
	}

	/**
	 * Gets stats of unsubscribed subscribers in last 12 months
	 * @param int $list_id
	 * @return multitype:number
	 */
	public function getUnsubsForLastYear($list_id = null)
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qb = $this->createQueryBuilder('p');
	    $qb->select('p')
	       ->andWhere('p.status = 2')
	       ->andWhere('YEAR(p.last_activity_at) = :year')
	       ->andWhere('MONTH(p.last_activity_at) = :month');

	    $stats = array();
		for ($i = 11; $i >= 0; $i--) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$monthPrint = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
			$year = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
			$yearPrint = date("y", strtotime( date( 'Y-m-01' )." -$i months"));

			$qb->setParameter('year', $year)->setParameter('month', $month);

			$stats['data'][] = count($qb->getQuery()->getResult());
			$stats['print'][] = "'".$monthPrint." ".$yearPrint."'";
		}

		return $stats;
	}

	/**
	 * Gets stats of complained subscribers in last 12 months
	 * @param int $list_id
	 * @return multitype:number
	 */
	public function getComplaintsForLastYear($list_id = null)
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qb = $this->createQueryBuilder('p');
	    $qb->select('p')
	       ->andWhere('p.status = 5')
	       ->andWhere('YEAR(p.last_activity_at) = :year')
	       ->andWhere('MONTH(p.last_activity_at) = :month');

	    $stats = array();
		for ($i = 11; $i >= 0; $i--) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$monthPrint = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
			$year = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
			$yearPrint = date("y", strtotime( date( 'Y-m-01' )." -$i months"));

			$qb->setParameter('year', $year)->setParameter('month', $month);

			$stats['data'][] = count($qb->getQuery()->getResult());
			$stats['print'][] = "'".$monthPrint." ".$yearPrint."'";
		}

		return $stats;
	}

}