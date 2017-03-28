<?php

namespace ApiBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class MassageUserRepository extends EntityRepository implements UserLoaderInterface {

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u') 
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByEmail($email) {
        return $this->createQueryBuilder('u') 
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
