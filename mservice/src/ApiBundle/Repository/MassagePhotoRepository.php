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
                        ->orderBy('p.id', 'DESC')
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

    public function getPhotoListBo($only_total = false, $offset = 0, $limit = 25, $type = null, $is_deleted = null, $word = null) {
        $q = $this->createQueryBuilder('p');
        $q->select('p.id, p.userId, p.photoType, p.postId, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.isDeleted, p.internalId, p.created, p.updated');

        if ($only_total) {
            $q->select(' count(p.id) ');
        }

        $q->where(' 1 = 1 ');

        if (strlen($is_deleted) > 0) {
            if ($is_deleted == 1) {
                $q->andWhere('p.isDeleted = :is_deleted');
                $parameters[':is_deleted'] = UtileService::TINY_INT_TRUE;
            } else {
                $q->andWhere('p.isDeleted = :is_deleted');
                $parameters[':is_deleted'] = UtileService::TINY_INT_FALSE;
            }
        }

        if (strlen($word) > 0) {
            $q->andWhere('p.title LIKE :title');
            $parameters[':title'] = '%' . $word . '%';
        }

        $q->orderBy('p.updated', 'DESC');

        if (isset($parameters)) {
            $q->setParameters($parameters);
        }

        $q->distinct();

        if ($only_total) {
            //return count($q->getQuery()->getResult());
            $total = $q->getQuery()->getResult();
            return $total[0][1];
        }

        $q->setMaxResults($limit);
        $q->setFirstResult($offset);
        $photos = $q->getQuery()->getResult();
        return $photos;
    }

    public function loadDeletedProfilePhotosByUserId($user_id) {
        return $this->createQueryBuilder('p')
                        ->select('p.id, p.photoType, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.internalId, p.created')
                        ->where('p.userId = :user_id AND p.photoType = :type AND p.isDeleted = 1 AND p.postId IS NULL')
                        ->setParameter('user_id', $user_id)
                        ->setParameter('type', PhotoService::PROFILE_PHOTO_TYPE)
                        ->orderBy('p.id', 'DESC')
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function loadDeletedUserPhotosByUserId($user_id) {
        return $this->createQueryBuilder('p')
                        ->select('p.id, p.photoType, p.photoOrigin, p.photoMedium, p.photoSmall, p.photoIcon, p.title, p.internalId, p.created')
                        ->where('p.userId = :user_id AND p.photoType = :type AND p.isDeleted = 1 AND p.postId IS NULL')
                        ->setParameter('user_id', $user_id)
                        ->setParameter('type', PhotoService::USER_PHOTO_TYPE)
                        ->getQuery()
                        ->getResult()
        ;
    }

}
