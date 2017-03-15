<?php
namespace SonnyBlaine\Integrator\Services;

use Doctrine\DBAL\Connection;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RabbitProducer;
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
     * @var RabbitProducer
     */
    protected $rabbitProducer;

    /**
     * IntegratorService constructor.
     * @param Connection $connection
     * @param SourceService $sourceService
     * @param RequestService $requestService
     * @param RabbitProducer $rabbitProducer
     */
    public function __construct(
        Connection $connection,
        SourceService $sourceService,
        RequestService $requestService,
        RabbitProducer $rabbitProducer
    ) {
        $this->connection = $connection;
        $this->sourceService = $sourceService;
        $this->requestService = $requestService;
        $this->rabbitProducer = $rabbitProducer;
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

            $this->requestService->createDestinationRequest($sourceRequest);

            $this->rabbitProducer->publish($sourceRequest->getId());
        });

        return $sourceRequest;
    }
}