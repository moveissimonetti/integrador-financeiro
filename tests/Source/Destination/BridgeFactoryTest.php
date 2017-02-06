<?php
namespace SonnyBlaine\Integrator\Tests;

use Pimple\Container;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\Integrator\BridgeInterface;
use SonnyBlaine\Integrator\Destination\Request;

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

        $bridge = (new class() implements BridgeInterface
        {
            public function integrate(Request $request)
            {
                return true;
            }
        });

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

        $bridge = (new class() implements BridgeInterface
        {
            public function integrate(Request $request)
            {
                return true;
            }
        });

        $factory->addBridge($bridge, 'key');

        $factory->factory('');
    }


    public function testFactoryMustReturnCorrectBridge()
    {
        $factory = new BridgeFactory($this->container);

        $bridge = (new class() implements BridgeInterface
        {
            public function integrate(Request $request)
            {
                return true;
            }
        });

        $factory->addBridge($bridge, 'key');

        $return = $factory->factory('key');

        $this->assertEquals($bridge, $return);
    }
}
