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
                        ->orderBy('p.id', 'DESC')
                        ->getQuery()
                        ->getResult();
    }
    
    public function loadPostsBo($only_total = false, $offset = 0, $limit = 25, $country_id = null, $lang = null, $is_deleted = null, $word = null) {
        $q = $this->createQueryBuilder('p');
        
        $q->select('p.id, p.userId, p.internalId, p.categoryId, p.draftId, p.title, p.description,
                p.isZh, p.isFr, p.isEn, p.isDeleted, p.topTime, p.created, p.updated');
        
        if($only_total){
            $q->select(' count(p.id) ');
        }
        
        $q->innerJoin('ApiBundle:Muser', 'u', 'WITH', 'u.id = p.userId');
        $q->leftJoin('ApiBundle:Mcountry', 'c', 'WITH', 'c.id = u.countryId');
        $q->leftJoin('ApiBundle:Mlocation', 'l', 'WITH', 'l.id = u.locationId');
        
        $q->where(' 1 = 1 ');
        
        if (strlen($country_id) > 0) {
            $q->andWhere('u.countryId = :country_id');
            $parameters[':country_id'] = $country_id;
        }
        
        if (strlen($lang)) {
            switch ($lang) {
                case UtileService::LANG_ZH :
                    $q->andWhere('p.isZh = :is_zh');
                    $parameters[':is_zh'] = UtileService::TINY_INT_TRUE;
                    break;
                case UtileService::LANG_FR :
                    $q->andWhere('p.isFr = :is_fr');
                    $parameters[':is_fr'] = UtileService::TINY_INT_TRUE;
                    break;
                case UtileService::LANG_EN :
                    $q->andWhere('p.isEn = :is_en');
                    $parameters[':is_en'] = UtileService::TINY_INT_TRUE;
                    break;
                default :
                    break;
            }
        }
        
        if (strlen($is_deleted) > 0) {
            if ($is_deleted == 1) {
                $q->andWhere('p.isDeleted = :is_deleted');
                $parameters[':is_deleted'] = UtileService::TINY_INT_TRUE;
            } else {
                $q->andWhere('p.isDeleted != :is_deleted');
                $parameters[':is_deleted'] = UtileService::TINY_INT_TRUE;
            }
        }
        
        if (strlen($word) > 0) {
            $q->andWhere('p.title LIKE :title');
            $q->orWhere('p.description LIKE :description');
            $parameters[':title'] = '%' . $word . '%';
            $parameters[':description'] = '%' . $word . '%';
        }
        
        $q->orderBy('p.updated', 'DESC');
        
        if ($only_total) {
            //return count($q->getQuery()->getResult());
            $total = $q->getQuery()->getResult();
            return $total[0][1];
        }
        
        $q->setMaxResults($limit);
        $q->setFirstResult($offset);
        $posts = $q->getQuery()->getResult();
        return $posts;  
    }

}
