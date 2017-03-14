<?php
namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\Services\IntegratorService;
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
     * IntegratorController constructor.
     * @param IntegratorService $integratorService
     */
    public function __construct(IntegratorService $integratorService)
    {
        $this->integratorService = $integratorService;
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
}