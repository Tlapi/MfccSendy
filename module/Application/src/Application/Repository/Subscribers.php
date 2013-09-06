<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class Subscribers extends EntityRepository
{

	/**
	 * Gets stats of total subscribers in last 12 months
	 * @return multitype:number
	 */
	public function getTotalForLastYear()
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qb = $this->createQueryBuilder('p');
	    $qb->select('p')
	       ->andWhere('YEAR(p.inserted_at) = :year')
	       ->andWhere('MONTH(p.inserted_at) = :month');

	    $stats = array();
		for ($i = 11; $i >= 0; $i--) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$monthPrint = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
			$year = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
			$yearPrint = date("y", strtotime( date( 'Y-m-01' )." -$i months"));

			$qb->setParameter('year', $year)->setParameter('month', $month);

			$stats['data'][] = count($qb->getQuery()->getResult());
			//$stats[12-$i]['month'] = $month;
			//$stats[12-$i]['year'] = $year;
			$stats['print'][] = "'".$monthPrint." ".$yearPrint."'";
		}

		return $stats;
	}

}