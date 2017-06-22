<?php

namespace SonnyBlaine\Integrator\Tests\Search;

use SonnyBlaine\Integrator\Search\SearchRequest;
use SonnyBlaine\Integrator\Search\SearchSource;

class SearchRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $data = new \stdClass();
        $identifier = "teste";

        $searchRequest = new SearchRequest($data, $identifier);

        $this->assertEquals($data, $searchRequest->getData());
        $this->assertEquals($identifier, $searchRequest->getMethodIdentifier());
    }
}