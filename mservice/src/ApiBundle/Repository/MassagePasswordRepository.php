<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MassagePasswordRepository extends EntityRepository {

    public function loadUserPasswordsByUserId($user_id) {
        return $this->createQueryBuilder('p')
                        ->where('p.userId = :user_id')
                        ->setParameter('user_id', $user_id)
                        ->getQuery()
                        ->getResult()
        ;
    }

}
