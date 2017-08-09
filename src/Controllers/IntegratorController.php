<?php

namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\Services\IntegratorService;
use SonnyBlaine\Integrator\Services\SearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IntegratorController
 * @package SonnyBlaine\Integrator\Controllers
 */
class IntegratorController
{
    /**
     * @var IntegratorService
     */
    protected $integratorService;

    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * IntegratorController constructor.
     * @param IntegratorService $integratorService
     */
    public function __construct(IntegratorService $integratorService, SearchService $searchService = null)
    {
        $this->integratorService = $integratorService;
        $this->searchService = $searchService;
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
            $sourceRequest = $this->integratorService->integrate($sourceIdentifier, $queryParameter);

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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request, $sourceIdentifier)
    {
        try {
            $params = [
                $sourceIdentifier,
                (object)$_GET
            ];

            $search = $this->searchService
                ->search(...$params);

            return new JsonResponse(
                [
                    'error' => false,
                    'origin' => $search->getOriginName(),
                    'data' => $search->getResult()->jsonSerialize()
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => true,
                'data' => $e->getMessage()
            ]);
        }
    }
}