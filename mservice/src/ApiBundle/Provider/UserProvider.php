<?php
namespace ApiBundle\Provider;

use FOS\ElasticaBundle\Provider\ProviderInterface;
use Elastica\Type;
use Elastica\Document;

class UserProvider implements ProviderInterface
{
    protected $userType;

    public function __construct(Type $userType)
    {
        $this->userType = $userType;
    }

    /**
     * Insert the repository objects in the type index
     *
     * @param \Closure $loggerClosure
     * @param array    $options
     */
    public function populate(\Closure $loggerClosure = null, array $options = array())
    {
        $batchSize = 1;
        $totalObjects = 1;

        if ($loggerClosure) {
            $loggerClosure($batchSize, $totalObjects, 'Indexing users');
        }

        $document = new Document();
        $document->setData(array('username' => 'Bob'));
        $this->userType->addDocuments(array($document));
    }
}