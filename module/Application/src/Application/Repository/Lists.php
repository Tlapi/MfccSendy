<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class Lists extends EntityRepository
{

	/**
	 * Find lists by array of ids
	 */
	public function findByIds($ids_array){
		
		$qb = $this->createQueryBuilder('p');
		$qb->select('p');
		
		$qb->add('where', $qb->expr()->in('p.id', '?1'));
		$qb->setParameter(1, $ids_array);
		
		return $query = $qb->getQuery()->getResult();
		
	}

}