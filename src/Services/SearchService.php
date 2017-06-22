<?php

namespace SonnyBlaine\Integrator\Services;

use Doctrine\ORM\EntityRepository;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\Integrator\Search\SearchRequest;
use SonnyBlaine\Integrator\Search\SearchSource;
use Symfony\Component\Config\Definition\Exception\Exception;

class SearchService
{
    /**
     * @var EntityRepository
     */
    private $searchSourceRepository;

    /**
     * @var BridgeFactory
     */
    private $bridgeFactory;

    /**
     * SearchService constructor.
     * @param EntityRepository $searchSourceRepository
     * @param BridgeFactory $bridgeFactory
     */
    public function __construct(EntityRepository $searchSourceRepository, BridgeFactory $bridgeFactory)
    {
        $this->searchSourceRepository = $searchSourceRepository;
        $this->bridgeFactory = $bridgeFactory;
    }

    /**
     * @param string $sourceIdentifier
     * @param \stdClass $parameters
     * @return mixed
     */
    public function search(string $sourceIdentifier, \stdClass $parameters)
    {
        /**
         * @var SearchSource $searchSource
         */
        $searchSource = $this->searchSourceRepository->findOneBy([
            'sourceId' => $sourceIdentifier
        ]);


        if (!$searchSource) {
            throw new Exception("Sorry, search source not found!");
        }

        return $this->bridgeFactory
            ->factory($searchSource->getBridge())
            ->search(
                new SearchRequest($parameters, $searchSource->getMethodId())
            );
    }
}