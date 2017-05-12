<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use Symfony\Component\DependencyInjection\Container;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use Elastica\Result;

class SearchService {
    /**
     * @var Container
     */
    public $container;
    /**
     *
     * @var UtileService
     */
    protected $utileService;
    
    protected $indexManager;

    protected $transformer;
    
    protected $resultSet;


    public function __construct(Container $container, UtileService $utileService, $indexManager) //, ElasticaToModelTransformerInterface $transformer)
    {
        $this->container = $container;
        $this->utileService = $utileService;
        $this->indexManager = $indexManager;
        //$this->transformer = $transformer;
    }
    
    public function searchManager($only_total, $offset, $limit, $country_id, $location_id, $color,
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
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
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

        if(strlen($word)){
            $wordQuery = new \Elastica\Query\QueryString();
            $wordQuery->setQuery($word); 
            $boolQuery->addShould($wordQuery);
        }
        
        if($only_total){
            return $search->search($boolQuery)->getTotalHits(); 
        }
        $this->resultSet = $search->search($boolQuery)->getResults($offset, $limit);
        return $this->getSourceArray();
        //return $this->transformer->hybridTransform($results);
    }
    
    public function getSourceArray()
    {
        return array_map(function (Result $result) {
            return $result->getSource();
        }, $this->resultSet);
    }        
}

