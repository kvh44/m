<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MassagePhotoRepository extends EntityRepository {
    
    public function loadPhotosByUserId($user_id) {
        return $this->createQueryBuilder('p') 
            ->where('p.userId = :user_id AND p.isDeleted != is_deleted AND p.postId IS NULL')
            ->setParameter('user_id', $user_id)
            ->setParameter('is_deleted', 1)
            ->getQuery()
            ->getResult()
        ;

    }
    
    public function loadPhotosByPostId($post_id) {
        return $this->createQueryBuilder('p') 
            ->where('p.postId = :post_id AND p.isDeleted != is_deleted AND p.postId IS NOT NULL')
            ->setParameter('post_id', $post_id)
            ->setParameter('is_deleted', 1)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function loadPhotoById($photo_id) {
        return $this->createQueryBuilder('p') 
            ->where('p.id = :id')
            ->setParameter('id', $photo_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadPhotoByInternalId($photo_internal_id) {
        return $this->createQueryBuilder('p') 
            ->where('p.internalId = :internal_id')
            ->setParameter('internal_id', $photo_internal_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
