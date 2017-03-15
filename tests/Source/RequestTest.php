<?php
namespace SonnyBlaine\Integrator\Tests\Source;

use SonnyBlaine\Integrator\Source\Request;
use SonnyBlaine\Integrator\Source\Source;

/**
 * Class RequestTest
 * @package SonnyBlaine\Integrator\Tests\Source
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    protected function getSource()
    {
        return $this->getMockBuilder(Source::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testConstructor()
    {
        $source = $this->getSource();

        $queryParameter = '123';

        $request = new Request($source, $queryParameter);

        $this->assertInstanceOf(Source::class, $request->getSource());
        $this->assertEquals($source, $request->getSource());
        $this->assertEquals($queryParameter, $request->getQueryParameter());
    }
}
