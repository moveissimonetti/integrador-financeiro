<?php

namespace SonnyBlaine\Integrator\Search;

use SonnyBlaine\IntegratorBridge\SearchRequestInterface;

/**
 * Class SearchRequest
 * @package SonnyBlaine\Integrator\Search
 */
class SearchRequest implements SearchRequestInterface
{
    /**
     * @var \stdClass
     */
    private $data;

    /**
     * @var string
     */
    private $identifier;

    /**
     * SearchRequest constructor.
     * @param $data
     * @param $identifier
     */
    public function __construct(\stdClass $data, string $identifier)
    {
        $this->data = $data;
        $this->identifier = $identifier;
    }

    /**
     * Data object to be integrated
     * @return \stdClass
     */
    public function getData(): \stdClass
    {
        return $this->data;
    }

    /**
     * Method identifier to be used for integration
     * @return string
     */
    public function getMethodIdentifier(): string
    {
        return $this->identifier;
    }
}