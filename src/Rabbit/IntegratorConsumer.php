<?php
namespace SonnyBlaine\Integrator\Rabbit;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RabbitProducer;
use SonnyBlaine\Integrator\BridgeFactory;
use SonnyBlaine\Integrator\Services\RequestService;

/**
 * Class IntegratorConsumer
 * @package SonnyBlaine\Integrator\Rabbit
 */
class IntegratorConsumer implements ConsumerInterface
{
    /**
     * @var RequestService
     */
    protected $requestService;

    /**
     * @var RabbitProducer
     */
    protected $rabbitProducer;

    /**
     * @var BridgeFactory
     */
    protected $bridgeFactory;

    /**
     * IntegratorConsumer constructor.
     * @param RequestService $requestService
     * @param RabbitProducer $rabbitProducer
     * @param BridgeFactory $bridgeFactory
     */
    public function __construct(
        RequestService $requestService,
        RabbitProducer $rabbitProducer,
        BridgeFactory $bridgeFactory
    ) {
        $this->requestService = $requestService;
        $this->rabbitProducer = $rabbitProducer;
        $this->bridgeFactory = $bridgeFactory;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $sourceRequest = $this->requestService->findSourceRequest($msg->body);

            echo "Starting integration. Source: " . $sourceRequest->getSourceIdentifier() . PHP_EOL;

            foreach ($sourceRequest->getDestinationRequests() as $destinationRequest) {
                echo "Integrating with " . $destinationRequest->getDestinationIdentifier() . PHP_EOL;

                $bridge = $this->bridgeFactory->factory($destinationRequest->getBridge());
                $bridge->integrate($destinationRequest);
            }

            echo "Integration completed" . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;

            $this->rabbitProducer->publish($msg->body);
        }
    }
}