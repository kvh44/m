<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ApiBundle\Services\PhotoService;

class MassagePhotoRepository extends EntityRepository {
    
    public function loadUserPhotosByUserId($user_id) {
        return $this->createQueryBuilder('p') 
            ->select('p.id, p.photoType, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.internalId, p.created')    
            ->where('p.userId = :user_id AND p.photoType = :type AND p.isDeleted IS NULL AND p.postId IS NULL')
            ->setParameter('user_id', $user_id)
            ->setParameter('type', PhotoService::USER_PHOTO_TYPE)
            ->getQuery()
            ->getResult()
        ;

    }
    
    public function loadProfilePhotosByUserId($user_id) {
        return $this->createQueryBuilder('p') 
            ->select('p.id, p.photoType, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.internalId, p.created')    
            ->where('p.userId = :user_id AND p.photoType = :type AND p.isDeleted IS NULL AND p.postId IS NULL')
            ->setParameter('user_id', $user_id)
            ->setParameter('type', PhotoService::PROFILE_PHOTO_TYPE)
            ->orderBy('p.id','DESC')  
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

    }
    
    public function loadPhotosByPostId($post_id) {
        return $this->createQueryBuilder('p') 
            ->select('p.id, p.photoType, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.internalId, p.created')        
            ->where('p.postId = :post_id AND p.isDeleted IS NULL')
            ->setParameter('post_id', $post_id)
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
            ->where('p.internalId = :internal_id AND p.isDeleted IS NULL')
            ->setParameter('internal_id', $photo_internal_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
