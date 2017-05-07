<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use Symfony\Component\DependencyInjection\Container;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;

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
    
    public function __construct(Container $container, UtileService $utileService, $indexManager) //, ElasticaToModelTransformerInterface $transformer)
    {
        $this->container = $container;
        $this->utileService = $utileService;
        $this->indexManager = $indexManager;
        //$this->transformer = $transformer;
    }
    
    public function searchManager($offset, $length, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word)
    {
        return $this->searchUserByIndex($offset, $length, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word);
    }        

    public function searchUserByIndex($offset = 0, $length = 15, $country_id = null, $location_id = null, 
            $color = null, $lang = null, $is_single = null, $age_period = array(), $word = null)
    {
        $search = $this->container->get($this->indexManager)->getIndex('app')->createSearch();
        $search->addType('user');
        
        $boolQuery = new \Elastica\Query\BoolQuery();
        $activeQuery = new \Elastica\Query\Terms();
        $activeQuery->setTerms('isActive', array(UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING, UtileService::BOOL_TRUE));
        $boolQuery->addMust($activeQuery);
        
        if($country_id){
            $countryQuery = new \Elastica\Query\Terms();
            $countryQuery->setTerms('countryId', array($country_id));
            $boolQuery->addMust($countryQuery);
        }
        
        if($location_id){
            $locationQuery = new \Elastica\Query\Terms();
            $locationQuery->setTerms('locationId', array($location_id));
            $boolQuery->addMust($locationQuery);
        }
        
        if($color){
            $colorQuery = new \Elastica\Query\Terms();
            $colorQuery->setTerms('skinColor', array($color));
            $boolQuery->addMust($colorQuery);
        }
        
        if($lang){
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
        
        if($is_single){
            $singleQuery = new \Elastica\Query\Terms();
            $singleQuery->setTerms('isSingle', array(UtileService::BOOL_TRUE, UtileService::TINY_INT_TRUE, UtileService::TINY_INT_TRUE_STRING));
            $boolQuery->addMust($singleQuery);
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
        
        if($word){
            /*
            $wordQuery = new \Elastica\Query\Match();
            $wordQuery->setFieldQuery('nickname', $word);    
            $wordQuery->setFieldQuery('wechat', $word);   
            $wordQuery->setFieldQuery('facebook', $word);   
            $wordQuery->setFieldQuery('instagram', $word);   
            $wordQuery->setFieldQuery('website', $word);   
            $wordQuery->setFieldQuery('country', $word);   
            $wordQuery->setFieldQuery('city', $word);   
            $wordQuery->setFieldQuery('shopAddress', $word);   
            $wordQuery->setFieldQuery('description', $word);   
            $wordQuery->setFieldQuery('translatedDescription', $word);   
            $boolQuery->addShould($wordQuery);
             *
             */
            $boolQuery = $word;
        }
         
        
        return $results = $search->search($boolQuery)->getResults($offset, $length);
        //return $this->transformer->hybridTransform($results);
    }        
}

