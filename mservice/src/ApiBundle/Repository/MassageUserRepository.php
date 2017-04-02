<?php

namespace ApiBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class MassageUserRepository extends EntityRepository implements UserLoaderInterface {

    public function loadUserByIdentifier($identifier)
    {
        return $this->createQueryBuilder('u') 
            ->where('u.username = :username or u.email = :email or u.telephone = :telephone')
            ->setParameter('username', $identifier)
            ->setParameter('email', $identifier)
            ->setParameter('telephone', $identifier)    
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadPasswordIndicationByIdentifier($identifier)
    {
        return $this->createQueryBuilder('u') 
            ->select('u.id, u.email, u.username, u.telephone, p.id, p.indication')    
            ->innerJoin('ApiBundle:Mpassword', 'p','WITH','p.userId = u.id')    
            ->where('u.username = :username or u.email = :email or u.telephone = :telephone')
            ->setParameter('username', $identifier)
            ->setParameter('email', $identifier)
            ->setParameter('telephone', $identifier)    
            ->orderBy('p.id','DESC')    
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadPasswordByUserInternalId($internalId)
    {
        return $this->createQueryBuilder('u') 
            ->select('p.password')    
            ->innerJoin('ApiBundle:Mpassword', 'p','WITH','p.userId = u.id')    
            ->where('u.internalId = :internal_id')
            ->setParameter('internal_id', $internalId)   
            ->orderBy('p.id','DESC')    
            ->getQuery()
            ->getOneOrNullResult();
    }        

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
    
    public function loadUserByTelephone($telephone) {
        return $this->createQueryBuilder('u') 
            ->where('u.telephone = :telephone')
            ->setParameter('telephone', $telephone)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
