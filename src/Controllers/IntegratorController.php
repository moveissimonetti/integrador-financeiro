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
            if ($request->headers->get('X-APP-ENV') != APP_ENV) {
                return new JsonResponse([
                    'requestId' => null,
                    'error' => 'Not allowed. Please, make sure you are using the correct environment.',
                ], JsonResponse::HTTP_NOT_ACCEPTABLE);
            }

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