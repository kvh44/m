<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MassageCategoryRepository extends EntityRepository {
    
    public function loadBoCategories()
    {
        $c = $this->createQueryBuilder('c');
        $categories = $c->getQuery()->getResult();
        
        return $categories;
    }
    
    public function loadCategories()
    {
        $c = $this->createQueryBuilder('c');
        $categories = $c
                ->where('c.isDeleted IS NULL')
                ->getQuery()
                ->getResult();
        
        return $categories;
    }
    
}

    
