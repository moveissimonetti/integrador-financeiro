<?php

namespace SonnyBlaine\Integrator\Tests\Search;

use SonnyBlaine\Integrator\Search\SearchSource;

class SearchSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $sourceId = "simonetti.rovereti.test";
        $methodId = "TesteMethod";
        $bridge = "rovereti.bridge";

        $searchSource = new SearchSource($sourceId, $methodId, $bridge);

        $this->assertEquals($sourceId, $searchSource->getSourceId());
        $this->assertEquals($methodId, $searchSource->getMethodId());
        $this->assertEquals($bridge, $searchSource->getBridge());
    }
}