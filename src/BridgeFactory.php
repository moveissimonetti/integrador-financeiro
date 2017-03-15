<?php
namespace SonnyBlaine\Integrator;

use Pimple\Container;
use SonnyBlaine\IntegratorBridge\BridgeInterface;

class BridgeFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * BridgeFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $bridgeIdentifier
     * @return BridgeInterface
     * @throws \Exception
     */
    public function factory(string $bridgeIdentifier): BridgeInterface
    {
        if (!$this->container->offsetExists($bridgeIdentifier)) {
            throw new \Exception('Bridge not found');
        }

        return $this->container->offsetGet($bridgeIdentifier);
    }
}