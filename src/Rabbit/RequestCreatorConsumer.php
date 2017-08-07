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
    const MAX_TRY_COUNT = 20;

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
        $sourceRequest = $tryCount = null;

        try {
            $sourceRequest = $this->requestService->findSourceRequest($msg->body);

            echo "Initializing requests creation. Source: " . $sourceRequest->getSourceIdentifier() . PHP_EOL;

            $tryCount = $sourceRequest->getTryCount() + 1;

            $this->requestService->updateTryCount($sourceRequest, $tryCount);

            echo "Attempt {$tryCount}..." . PHP_EOL;

            if (empty($sourceRequest->getDestinationRequests()->count())) {
                echo "Creating requests..." . PHP_EOL;
                $this->requestService->createDestinationRequest($sourceRequest);
            }

            foreach ($sourceRequest->getDestinationRequests() as $destinationRequest) {
                echo "Publishing to integrate at {$destinationRequest->getDestinationIdentifier()}..." . PHP_EOL;
                $this->integratorProducer->publish($destinationRequest->getId());
            }

            $sourceRequest->setSuccessIn(new \DateTime());
            
            $this->requestService->updateSourceRequestResponse($sourceRequest, true);

            echo "Process completed" . PHP_EOL;

            return true;
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage() . PHP_EOL;

            if ($sourceRequest && self::MAX_TRY_COUNT == $tryCount) {
                $this->requestService->updateSourceRequestResponse(
                    $sourceRequest,
                    false,
                    $e->getMessage(),
                    $e->getTraceAsString()
                );

                return true;
            }

            $this->requestCreatorProducer->publish($msg->body);

            return true;
        }
    }
}