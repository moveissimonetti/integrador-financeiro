<?php
namespace SonnyBlaine\Integrator\Tests;

use Pimple\Container;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\IntegratorBridge\BridgeInterface;
use SonnyBlaine\IntegratorBridge\IntegrateRequestInterface;
use SonnyBlaine\IntegratorBridge\SearchRequestInterface;

/**
 * Class BridgeFactoryTest
 * @package SonnyBlaine\Integrator\Tests
 */
class BridgeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Container
     */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Bridge not found
     */
    public function testFactoryMustThrowExceptionIfNotFoundBridge()
    {
        $factory = new BridgeFactory($this->container);
        $factory->factory('test.test');
    }

    public function testFactoryMustReturnCorrectBridge()
    {
        $bridge = $this->getBridge();

        $this->container->method('offsetExists')
            ->willReturn(true);

        $this->container->method('offsetGet')
            ->willReturn($bridge);

        $factory = new BridgeFactory($this->container);

        $return = $factory->factory('test.test');

        $this->assertEquals($bridge, $return);
    }

    protected function getBridge()
    {
        return (new class() implements BridgeInterface
        {
            public function integrate(IntegrateRequestInterface $request)
            {
                return true;
            }

            /**
             * Retrieves data
             * @param SearchRequestInterface $request
             * @return mixed
             */
            public function search(SearchRequestInterface $request)
            {
                return true;
            }
        });
    }
}
