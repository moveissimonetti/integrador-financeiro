<?php

namespace SonnyBlaine\Integrator\Rabbit;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RequestCreatorProducer;
use OldSound\RabbitMqBundle\RabbitMq\Producer as IntegratorProducer;
use PhpAmqpLib\Message\AMQPMessage;
use SonnyBlaine\Integrator\Services\RequestService;

/**
 * Class RequestCreatorConsumer
 * @package SonnyBlaine\Integrator\Rabbit
 */
class RequestCreatorConsumer implements ConsumerInterface
{
    /**
     * @var RequestService
     */
    protected $requestService;

    /**
     * @var RequestCreatorProducer
     */
    protected $requestCreatorProducer;

    /**
     * @var IntegratorProducer
     */
    protected $integratorProducer;

    /**
     * RequestCreatorConsumer constructor.
     * @param RequestService $requestService
     * @param IntegratorProducer $requestCreatorProducer
     * @param IntegratorProducer $integratorProducer
     */
    public function __construct(
        RequestService $requestService,
        IntegratorProducer $requestCreatorProducer,
        IntegratorProducer $integratorProducer
    ) {
        $this->requestService = $requestService;
        $this->requestCreatorProducer = $requestCreatorProducer;
        $this->integratorProducer = $integratorProducer;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            $sourceRequest = $this->requestService->findSourceRequest($msg->body);

            echo "Initializing requests creation. Source: " . $sourceRequest->getSourceIdentifier() . PHP_EOL;

            if (empty($sourceRequest->getDestinationRequests()->count())) {
                echo "Creating requests..." . PHP_EOL;
                $this->requestService->createDestinationRequest($sourceRequest);
            }

            echo "Publishing to integrate..." . PHP_EOL;
            $this->integratorProducer->publish($msg->body);

            echo "Process completed" . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;

            $this->requestCreatorProducer->publish($msg->body);
        }
    }
}