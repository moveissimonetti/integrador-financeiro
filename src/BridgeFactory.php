<?php
namespace SonnyBlaine\Integrator;

use Pimple\Container;

class BridgeFactory
{
    /**
     * @var array
     */
    protected $bridges = [];

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
     * @param BridgeInterface $bridge
     * @param string $key
     */
    public function addBridge(BridgeInterface $bridge, string $key)
    {
        $this->bridges[$key] = $bridge;
    }

    /**
     * @return array
     */
    public function getBridges(): array
    {
        return $this->bridges;
    }

    /**
     * @param string $bridgeIdentifier
     * @return BridgeInterface
     * @throws \Exception
     */
    public function factory(string $bridgeIdentifier): BridgeInterface
    {
        if (!isset($this->bridges[$bridgeIdentifier])) {
            throw new \Exception('Bridge not found');
        }

        return $this->bridges[$bridgeIdentifier];
    }
}