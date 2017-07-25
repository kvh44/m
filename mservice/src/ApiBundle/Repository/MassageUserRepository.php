<?php

namespace ApiBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;
use ApiBundle\Services\UtileService;

class MassageUserRepository extends EntityRepository implements UserLoaderInterface {

    public function loadUserByIdentifier($identifier)
    {
        return $this->createQueryBuilder('u') 
            ->where('u.username = :username or u.email = :email or u.telephone = :telephone')
            ->setParameter('username', $identifier)
            ->setParameter('email', $identifier)
            ->setParameter('telephone', $identifier)
            ->setMaxResults(1)
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
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadPasswordByUserInternalId($internalId)
    {
        return $this->createQueryBuilder('u') 
            ->select('p.id, p.password')    
            ->innerJoin('ApiBundle:Mpassword', 'p','WITH','p.userId = u.id')    
            ->where('u.internalId = :internal_id')
            ->setParameter('internal_id', $internalId)   
            ->orderBy('p.id','DESC')    
            ->setMaxResults(1)    
            ->getQuery()
            ->getOneOrNullResult();
    }        

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u') 
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByEmail($email) {
        return $this->createQueryBuilder('u') 
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByTelephone($telephone) {
        return $this->createQueryBuilder('u') 
            ->where('u.telephone = :telephone')
            ->setParameter('telephone', $telephone)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByInternalId($internal_id) {
        return $this->createQueryBuilder('u') 
            ->where('u.internalId = :internal_id')
            ->setParameter('internal_id', $internal_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByToken($token) {
        return $this->createQueryBuilder('u') 
            ->where('u.token = :token')
            ->setParameter('token', $token)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadUserByInternalToken($internal_token) {
        return $this->createQueryBuilder('u') 
            ->where('u.internalToken = :internal_token')
            ->setParameter('internal_token', $internal_token)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function loadInternalTokenByExternalToken($internal_id, $external_token) {
        return $this->createQueryBuilder('u') 
            ->select('u.internalToken')    
            ->where('u.externalToken = :external_token')
            ->andWhere('u.internalId = :internal_id')
            ->setParameters(array('internal_id' => $internal_id,'external_token' => $external_token))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function searchUserBySql($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array(), $em)
    {   
        $q = $this->createQueryBuilder('u');
        $q->select('u.id, u.username, u.email, u.telephone, u.nickname, u.wechat, u.facebook,
        u.instagram, u.website, u.timezone, u.country, u.city, u.postNumber, u.countryId, u.locationId,
        u.skinColor, u.birthday, u.shopAddress, u.description, u.translatedDescription, u.isActive, u.isDeleted,
        u.isPremium, u.isSingle, u.isShop, u.isZh, u.isEn, u.isFr, u.isTest, u.isFromOtherWeb, u.otherWeb,
        u.slug, u.internalId, u.topTime, u.lastSynchronizedFromOtherWebTime, u.paymentExpiredTime, u.created,
        u.updated');
        
        $q->where('u.isDeleted <> :is_deleted');
        $parameters = array(':is_deleted' => UtileService::TINY_INT_TRUE);
        
        if(strlen($country_id) > 0){
            $q->andWhere('u.countryId = :country_id');
            $parameters[':country_id'] = $country_id;
        }
        
        if(strlen($location_id) > 0){
            $q->andWhere('u.locationId = :location_id');
            $parameters[':location_id'] = $location_id;
        }
        
        if(strlen($color) > 0){
            $q->andWhere('u.skinColor = :skin_color');
            $parameters[':skin_color'] = $color;
        }
        
        if(strlen($lang) > 0){
            switch ($lang){
                case UtileService::LANG_ZH : 
                    $q->andWhere('u.isZh = :is_zh');
                    $parameters[':is_zh'] = UtileService::TINY_INT_TRUE;
                   break; 
               case UtileService::LANG_FR : 
                   $q->andWhere('u.isFr = :is_fr');
                   $parameters[':is_fr'] = UtileService::TINY_INT_TRUE;
                   break; 
               case UtileService::LANG_EN : 
                   $q->andWhere('u.isEn = :is_en');
                   $parameters[':is_en'] = UtileService::TINY_INT_TRUE;
                   break; 
               default :
                   break;
            }
        }
        
        if(strlen($is_single) > 0) {
            if($is_single == 1){
                $q->andWhere('u.isSingle = :is_single');
                $parameters[':is_single'] = UtileService::TINY_INT_TRUE;
            } else {
                $q->andWhere('u.isSingle = :is_single');
                $parameters[':is_single'] = UtileService::TINY_INT_FALSE;
            }
        }
        
        if(count($age_period) > 0){
            $thisYear = date('Y');
            
            if(array_key_exists('min', $age_period)){
                $maxBirthYear = (int)$thisYear - (int)$age_period['min'];
                $minTime = strtotime($maxBirthYear);
                $minTime = date('Y-m-d',$minTime);
                $q->andWhere('u.birthday < :min');
                $parameters[':min'] = $minTime;
            }
            if(array_key_exists('max', $age_period)){
                $minBirthYear = (int)$thisYear - (int)$age_period['max'];
                $maxTime = strtotime($minBirthYear);
                $maxTime = date('Y-m-d',$maxTime);
                $q->andWhere('u.birthday > :max');
                $parameters[':max'] = $maxTime;
            }

        }
		
	$q->orderBy('u.updated', 'DESC');
        
        $q->setParameters($parameters);
        $q->distinct();
        //var_dump($q->getQuery()->getSQL());
        //die;
        if($only_total){
            return count($q->getQuery()->getScalarResult());
        }
        $q->setMaxResults($limit);
        $q->setFirstResult($offset);
        $users = $q->getQuery()->getScalarResult();
        
        foreach ($users as $key => $user) {
            $profile_photo = $em->getRepository('ApiBundle:Mphoto')->loadProfilePhotosByUserId($user['id']);
            $users[$key][UtileService::DATA_STRUCTURE_PROFILE_PHOTO] = $profile_photo;
        }
        return $users;
    }
    
    public function getUserListBo($only_total = false, $offset = 0, $limit = 25, $country_id = null, $lang = null,
        $is_single = null, $is_active = null, $word = null)
    {
        $q = $this->createQueryBuilder('u');
        $q->select('u.id, u.username, u.email, u.telephone, u.wechat, c.countryEn, l.cityEn, l.postNumber,
        u.shopName, u.isActive, u.isDeleted, u.isSingle, u.isShop, u.isZh, u.isEn, u.isFr, u.isTest, u.isFromOtherWeb, u.otherWeb,
        u.internalToken, u.topTime, u.paymentExpiredTime, u.created, u.updated');
        
        if($only_total){
            $q->select(' count(u.id) ');
        }
        
        $q->leftJoin('ApiBundle:Mcountry', 'c','WITH','c.id = u.countryId');  
        $q->leftJoin('ApiBundle:Mlocation', 'l','WITH','l.id = u.locationId');        
        
        $q->where(' 1 = 1 ');
        
        if(strlen($country_id) > 0){
            $q->andWhere('u.countryId = :country_id');
            $parameters[':country_id'] = $country_id;
        }
        
        if(strlen($lang)){
            switch ($lang){
                case UtileService::LANG_ZH : 
                    $q->andWhere('u.isZh = :is_zh');
                    $parameters[':is_zh'] = UtileService::TINY_INT_TRUE;
                   break; 
               case UtileService::LANG_FR : 
                   $q->andWhere('u.isFr = :is_fr');
                   $parameters[':is_fr'] = UtileService::TINY_INT_TRUE;
                   break; 
               case UtileService::LANG_EN : 
                   $q->andWhere('u.isEn = :is_en');
                   $parameters[':is_en'] = UtileService::TINY_INT_TRUE;
                   break; 
               default :
                   break;
            }
        }
        
        if(strlen($is_active) > 0) {
            if($is_active == 1){
                $q->andWhere('u.isActive = :is_active');
                $parameters[':is_active'] = UtileService::TINY_INT_TRUE;
            } else {
                $q->andWhere('u.isActive = :is_active');
                $parameters[':is_active'] = UtileService::TINY_INT_FALSE;
            }
        }
        
        if(strlen($is_single) > 0) {
            if($is_single == 1){
                $q->andWhere('u.isSingle = :is_single');
                $parameters[':is_single'] = UtileService::TINY_INT_TRUE;
            } else {
                $q->andWhere('u.isSingle = :is_single');
                $parameters[':is_single'] = UtileService::TINY_INT_FALSE;
            }
        }
        
        if(strlen($word) > 0){
            $q->andWhere('u.username LIKE :username');
            $q->orWhere('u.telephone LIKE :telephone');
            $q->orWhere('u.wechat LIKE :wechat');
            $parameters[':username'] = '%'.$word.'%';
            $parameters[':telephone'] = '%'.$word.'%';
            $parameters[':wechat'] = '%'.$word.'%';
        }
        
        $q->orderBy('u.updated', 'DESC');
        
        if(isset($parameters)){
            $q->setParameters($parameters);
        }
        
        $q->distinct();
        
        if($only_total){
            //return count($q->getQuery()->getResult());
            $total = $q->getQuery()->getResult();
            return $total[0][1];
        }

        $q->setMaxResults($limit);
        $q->setFirstResult($offset);
        $users = $q->getQuery()->getResult();
        return $users;
    }        
}
