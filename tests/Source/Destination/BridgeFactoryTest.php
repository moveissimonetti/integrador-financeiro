<?php
namespace SonnyBlaine\Integrator\Tests;

use Pimple\Container;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\IntegratorBridge\BridgeInterface;
use SonnyBlaine\IntegratorBridge\RequestInterface;

/**
 * Class BridgeFactoryTest
 * @package SonnyBlaine\Integrator\Tests
 */
class BridgeFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAddBridgeMustIncreaseListOfBridges()
    {
        $factory = new BridgeFactory($this->container);

        $bridge = $this->getBridge();

        $this->assertCount(0, $factory->getBridges());

        $factory->addBridge($bridge, 'key');

        $this->assertCount(1, $factory->getBridges());
        $this->assertContainsOnlyInstancesOf(BridgeInterface::class, $factory->getBridges());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Bridge not found
     */
    public function testFactoryMustThrowExceptionIfNotFoundBridge()
    {
        $factory = new BridgeFactory($this->container);

        $bridge = $this->getBridge();

        $factory->addBridge($bridge, 'key');

        $factory->factory('');
    }


    public function testFactoryMustReturnCorrectBridge()
    {
        $factory = new BridgeFactory($this->container);

        $bridge = $this->getBridge();

        $factory->addBridge($bridge, 'key');

        $return = $factory->factory('key');

        $this->assertEquals($bridge, $return);
    }

    protected function getBridge()
    {
        return (new class() implements BridgeInterface
        {
            public function integrate(RequestInterface $request)
            {
                return true;
            }
        });
    }
}
