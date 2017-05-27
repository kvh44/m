<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MassagePostRepository extends EntityRepository {
	
	public function loadPostById($id) {
        return $this->createQueryBuilder('p') 
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
	
	public function loadPostByInternalId($internal_id) {
        return $this->createQueryBuilder('p') 
            ->where('p.internalId = :internal_id')
            ->setParameter('internal_id', $internal_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
	
	public function loadPostsByUserId($user_id) {
		return $this->createQueryBuilder('p') 
            ->where('p.userId = :user_id')
            ->setParameter('user_id', $user_id)
			->orderBy('p.id','DESC')   
            ->getQuery()
            ->getResult();
    }
	
}
