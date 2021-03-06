<?php

namespace Chitanka\LibBundle\Entity;

/**
 *
 */
class ForeignBookRepository extends EntityRepository
{
	public function getLatest($limit = null)
	{
		return $this->createQueryBuilder('b')
			->orderBy('b.isFree', 'desc')
			->addOrderBy('b.id', 'desc')
			->getQuery()->setMaxResults($limit)
			->getArrayResult();
	}
}
