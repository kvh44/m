<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use ApiBundle\Services\CacheService;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\Bundle\DoctrineBundle\Registry;
//use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use Elastica\Result;
use Elastica\Query;
use FOS\ElasticaBundle\Elastica\Client;

class SearchService {
    /**
     * @var Container
     */
    public $container;
    
     /**
     * @var Registry
     */
    protected $doctrine;

    /*
     * @var em
     */
    protected $em;
    
    /**
     *
     * @var UtileService
     */
    protected $utileService;
    
    protected $cacheService;
    
    protected $indexManager;

    protected $transformer;
    
    protected $resultSet;


    public function __construct(Container $container, Registry $doctrine, UtileService $utileService, CacheService $cacheService, $indexManager) //, ElasticaToModelTransformerInterface $transformer)
    {
        $this->container = $container;
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->utileService = $utileService;
        $this->cacheService = $cacheService;
        $this->indexManager = $indexManager;
        $this->client = new Client();
        //$this->transformer = $transformer;
    }
	
    public function searchPostManager($only_total, $offset, $limit, $country_id, $location_id, $word)
    {
        try{
            $searchResult = $this->searchPostByIndex($only_total, $offset, $limit, $country_id, $location_id, $word);
            $this->utileService->setResponseFrom(UtileService::FROM_SEARCH);
            $this->utileService->setResponseData($searchResult);
            $this->utileService->setResponseState(true);
            return $this->utileService->getResponse();
        } catch(\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }        
    
    public function searchUserManager($only_total, $offset, $limit, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word)
    {
        try{
            $searchResult = $this->searchUserByIndex($only_total, $offset, $limit, $country_id, $location_id, $color,
                    $lang, $is_single, $age_period, $word);
            $this->utileService->setResponseFrom(UtileService::FROM_SEARCH);
            $this->utileService->setResponseData($searchResult);
            $this->utileService->setResponseState(true);
            return $this->utileService->getResponse();
        } catch(\Exception $e) {
            try{
                $key = $this->getSearchUserKey($only_total, $offset, $limit, $country_id, $location_id, $color,
                    $lang, $is_single, $age_period);
                $searchUsersByKeyCache = $this->cacheService->getSearchUsersByKeyCache($key);
                if(!$searchUsersByKeyCache){
                    $results = $this->searchUserBySql($only_total, $offset, $limit, $country_id, $location_id, $color,
                        $lang, $is_single, $age_period);
                    $this->cacheService->setSearchUsersByKeyCache($key, serialize($results));
                    $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                } else {
                    $results = unserialize($searchUsersByKeyCache);
                    $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
                }
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseMessage($e->getMessage());
                $this->utileService->setResponseData($results);
                return $this->utileService->getResponse();
            } catch(\Exception $e) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($e->getMessage());
                return $this->utileService->getResponse();
            }
        }
    }        
	
    public function searchPostByIndex($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, $word = null)
    {
        $search = $this->container->get($this->indexManager)->getIndex('app')->createSearch();
        $search->addType('post');

        $boolQuery = new \Elastica\Query\BoolQuery();
        
        $activeQuery = new \Elastica\Query\Terms();
        $activeQuery->setTerms('isDeleted', array(UtileService::BOOL_TRUE, UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING));
        $boolQuery->addMustNot($activeQuery);
        
        if(strlen($word) > 0){
            $wordQuery = new \Elastica\Query\QueryString();
            $wordQuery->setQuery($word); 
            $boolQuery->addShould($wordQuery);
        }
                
        if($only_total){
            return $search->search($boolQuery)->getTotalHits(); 
        }
        $queryObject = Query::create($boolQuery);
        $queryObject->setSize($limit);
        $queryObject->setFrom($offset);        
        $this->resultSet = $search->search($queryObject)->getResults();
        return $this->getSourceArray();
    }

    public function searchUserByIndex($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array(), $word = null)
    {
        $search = $this->container->get($this->indexManager)->getIndex('app')->createSearch();
        $search->addType('user');
        
        $boolQuery = new \Elastica\Query\BoolQuery();
        $activeQuery = new \Elastica\Query\Terms();
        $activeQuery->setTerms('isActive', array(UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING, UtileService::BOOL_TRUE));
        $boolQuery->addMust($activeQuery);
        
        if(strlen($country_id) > 0){
            $countryQuery = new \Elastica\Query\Terms();
            $countryQuery->setTerms('countryId', array($country_id));
            $boolQuery->addMust($countryQuery);
        }
        
        if(strlen($location_id) > 0){
            $locationQuery = new \Elastica\Query\Terms();
            $locationQuery->setTerms('locationId', array($location_id));
            $boolQuery->addMust($locationQuery);
        }

        if(strlen($color) > 0){
            $colorQuery = new \Elastica\Query\Terms();
            $colorQuery->setTerms('skinColor', array($color));
            $boolQuery->addMust($colorQuery);
        }
       
        if(strlen($lang) > 0){
            $langQuery = new \Elastica\Query\Terms();
            
            switch ($lang){
                case UtileService::LANG_ZH : 
                   $langQuery->setTerms('isZh', array(UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING)); 
                   $boolQuery->addMust($langQuery);
                   break; 
               case UtileService::LANG_FR : 
                   $langQuery->setTerms('isFr', array(UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING)); 
                   $boolQuery->addMust($langQuery);
                   break; 
               case UtileService::LANG_EN : 
                   $langQuery->setTerms('isEn', array(UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING)); 
                   $boolQuery->addMust($langQuery);
                   break; 
               default :
                   break; 
            }
            
            
        }
        
        if(strlen($is_single) > 0) {
            if($is_single == 1){
                $singleQuery = new \Elastica\Query\Terms();
                $singleQuery->setTerms('isSingle', array(UtileService::BOOL_TRUE, UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING));
                $boolQuery->addMust($singleQuery);
            }

            if($is_single == 0){
                $singleQuery = new \Elastica\Query\Terms();
                $singleQuery->setTerms('isSingle', array(UtileService::BOOL_FALSE, UtileService::TINY_INT_FALSE, UtileService::TINY_INT_FALSE_STRING));
                $boolQuery->addMust($singleQuery);
            }
        }       
        
        if(count($age_period) > 0){
            $thisYear = date('Y');
            
            if(array_key_exists('min', $age_period)){
                $maxBirthYear = (int)$thisYear - (int)$age_period['min'];
                $time = strtotime($maxBirthYear);
                $time = date('Y-m-d',$time);
                $boolQuery->addFilter(new \Elastica\Filter\Range('birthday', array('lte' => $time)));
            }
            if(array_key_exists('max', $age_period)){
                $minBirthYear = (int)$thisYear - (int)$age_period['max'];
                $time = strtotime($minBirthYear);
                $time = date('Y-m-d',$time);
                $boolQuery->addFilter(new \Elastica\Filter\Range('birthday', array('gte' => $time)));
            }
        }

        if(strlen($word) > 0){
            $wordQuery = new \Elastica\Query\QueryString();
            $wordQuery->setQuery($word); 
            $boolQuery->addShould($wordQuery);
        }
        
        if($only_total){
            return $search->search($boolQuery)->getTotalHits(); 
        }
        $queryObject = Query::create($boolQuery);
        $queryObject->setSize($limit);
        $queryObject->setFrom($offset);        
        $this->resultSet = $search->search($queryObject)->getResults();
        return $this->getSourceArray();
        //return $search->search($queryObject)->getSuggests();
        //return $this->transformer->hybridTransform($results);
    }
    
    public function getSourceArray()
    {
        return array_map(function (Result $result) {
            return $result->getSource();
        }, $this->resultSet);
    }        
    
    public function getSearchEngineAliases()
    {
        try{
            $info = $this->client->request('_aliases', 'GET')->getData();
            if(is_array($info)){
                return true;
            } 
            return false;
        } catch(\Exception $e) {
            return false;
        }
    }        
    
    public function searchUserBySql($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array())
    {
        return $this->em->getRepository('ApiBundle:Muser')->searchUserBySql($only_total, $offset, $limit, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $this->em);
    }     
    
    public function searchUserByCache($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array())
    {
        $key = $this->getSearchUserKey($only_total, $offset, $limit, $country_id, $location_id, $color,
                    $lang, $is_single, $age_period);
        
        
    }
    
    public function getSearchUserKey($only_total = false, $offset = 0, $limit = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array())
    {
        $key = 'users';
        
        if(strlen($only_total) > 0){
            $key .= 'total'.$only_total;
        }
        
        if(strlen($offset) > 0){
            $key .= 'offset'.$offset;
        }
        if(strlen($limit) > 0){
            $key .= 'limit'.$limit;
        }
        if(strlen($country_id) > 0){
            $key .= 'country'.$country_id;
        }
        if(strlen($location_id) > 0){
            $key .= 'location'.$location_id;
        }
        if(strlen($color) > 0){
            $key .= 'color'.$color;
        }
        if(strlen($lang) > 0){
            $key .= 'lang'.$lang;
        }
        if(strlen($is_single) > 0){
            $key .= 'single'.$is_single;
        }
        if(count($age_period) > 0){
            if(array_key_exists('min', $age_period)){
                $key .= 'min'.$age_period['min'];
            }
            if(array_key_exists('max', $age_period)){
                $key .= 'max'.$age_period['max'];
            }
        }
        
        return $key;
    }        
}

