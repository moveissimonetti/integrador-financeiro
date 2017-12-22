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
    const MAX_TRY_COUNT = 20;

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
        $destinationRequest = $tryCount = null;

        try {
            $destinationRequest = $this->requestService->findDestinationRequest($msg->body);

            if (!$destinationRequest) {
                throw new \Exception('There is no Request to integrate.');
            }

            echo "Starting integration. Source: " . $destinationRequest->getSourceIdentifier() . ". Destination: " . $destinationRequest->getDestinationIdentifier() . PHP_EOL;

            $tryCount = $destinationRequest->getTryCount() + 1;

            $this->requestService->updateTryCount($destinationRequest, $tryCount);

            echo "Attempt {$tryCount}..." . PHP_EOL;

            $bridge = $this->bridgeFactory->factory($destinationRequest->getBridge());
            $bridge->integrate($destinationRequest);

            $destinationRequest->setSuccessIn(new \DateTime());

            $this->requestService->updateSourceRequestResponse($destinationRequest, true);

            echo "Integration completed" . PHP_EOL;

            return true;
        } catch (\Exception|\Error $e) {
            echo "Erro: " . $e->getMessage() . PHP_EOL;

            if ($destinationRequest && self::MAX_TRY_COUNT <= $tryCount) {
                $this->requestService->updateSourceRequestResponse(
                    $destinationRequest,
                    false,
                    $e->getMessage(),
                    $e->getTraceAsString()
                );

                return true;
            }

            $this->rabbitProducer->publish($msg->body);

            return true;
        }
    }
}