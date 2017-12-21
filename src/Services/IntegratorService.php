<?php

namespace SonnyBlaine\Integrator\Services;

use Doctrine\DBAL\Connection;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RequestCreatorProducer;
use OldSound\RabbitMqBundle\RabbitMq\Producer as IntegratorProducer;
use SonnyBlaine\Integrator\Source\Request;

/**
 * Class IntegratorService
 * @package SonnyBlaine\Integrator\Services
 */
class IntegratorService
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var SourceService Service class for Source
     */
    protected $sourceService;

    /**
     * @var RequestService Service class for Request
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
     * IntegratorService constructor.
     * @param Connection $connection
     * @param SourceService $sourceService
     * @param RequestService $requestService
     * @param RequestCreatorProducer $rabbitProducer
     */
    public function __construct(
        Connection $connection,
        SourceService $sourceService,
        RequestService $requestService,
        RequestCreatorProducer $requestCreatorProducer,
        IntegratorProducer $integratorProducer
    )
    {
        $this->connection = $connection;
        $this->sourceService = $sourceService;
        $this->requestService = $requestService;
        $this->requestCreatorProducer = $requestCreatorProducer;
        $this->integratorProducer = $integratorProducer;
    }

    /**
     * @param string $sourceIdentifier
     * @param string $queryParameter
     * @return Request
     */
    public function integrate(string $sourceIdentifier, string $queryParameter)
    {
        $sourceRequest = null;

        $this->connection->transactional(function () use ($sourceIdentifier, $queryParameter, &$sourceRequest) {
            $source = $this->sourceService->findByIdentifier($sourceIdentifier);

            $sourceRequest = $this->requestService->createSourceRequest($source, $queryParameter);

            if (is_null($sourceRequest->getDestinationRequests()) || empty($sourceRequest->getDestinationRequests()->count())) {
                $this->requestCreatorProducer->publish($sourceRequest->getId());
            }
        });

        return $sourceRequest;
    }

    /**
     * @param Request $sourceRequest
     * @return bool
     */
    public function canBeReinstated(Request $sourceRequest)
    {
        if ($sourceRequest->isCancelled() && $sourceRequest->isSuccess()) {
            return false;
        }

        foreach ($sourceRequest->getDestinationRequests() as $destinationRequest) {
            if ($destinationRequest->isSuccess() || $destinationRequest->isCancelled()) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $sourceRequestId
     * @throws \Exception
     */
    public function retryIntegrate(string $sourceRequestId)
    {
        $sourceRequest = $this->requestService->findSourceRequest($sourceRequestId);

        if (is_null($sourceRequest)) {
            throw new \Exception("Source Request not found.");
        }

        if (!$this->canBeReinstated($sourceRequest)) {
            throw new \Exception("This request can not be reinstated!");
        }

        if (!$sourceRequest->isSuccess()) {
            $this->requestService->updateTryCount($sourceRequest, 0);
            $this->requestCreatorProducer->publish($sourceRequestId);

            return;
        }

        foreach ($sourceRequest->getDestinationRequests() as $destinationRequest) {
            if ($destinationRequest->isSuccess() || $destinationRequest->isCancelled()) {
                continue;
            }

            $this->requestService->updateTryCount($destinationRequest, 0);
            $this->integratorProducer->publish($destinationRequest->getId());
        }
    }
}