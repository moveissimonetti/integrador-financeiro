<?php
namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Services\SourceService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RabbitProducer;

/**
 * Class IntegratorController
 * @package SonnyBlaine\Integrator\Controllers
 */
class IntegratorController
{
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
     * IntegratorController constructor.
     * @param SourceService $sourceService
     * @param RequestService $requestService
     * @param RabbitProducer $rabbitProducer
     */
    public function __construct(
        SourceService $sourceService,
        RequestService $requestService,
        RabbitProducer $rabbitProducer
    ) {
        $this->sourceService = $sourceService;
        $this->requestService = $requestService;
        $this->rabbitProducer = $rabbitProducer;
    }

    /**
     * @param Request $request Http Request
     * @param string $sourceIdentifier Identifier for Source
     * @param string $queryParameter Query Parameter
     * @return JsonResponse
     */
    public function integrateAction(Request $request, string $sourceIdentifier, string $queryParameter)
    {
        try {
            $source = $this->sourceService->findByIdentifier($sourceIdentifier);

            $sourceRequest = $this->requestService->createSourceRequest($source, $queryParameter);

            $this->requestService->createDestinationRequest($sourceRequest);

            $this->rabbitProducer->publish($sourceRequest->getId());

            return new JsonResponse([
                'requestId' => $sourceRequest->getId(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'requestId' => null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}