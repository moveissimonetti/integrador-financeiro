<?php

namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\RequestRepositoryInterface;
use SonnyBlaine\Integrator\Services\IntegratorService;
use SonnyBlaine\Integrator\Services\SearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SonnyBlaine\Integrator\Source\RequestRepository as SourceRequestRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class IntegratorController
 * @package SonnyBlaine\Integrator\Controllers
 */
class IntegratorController
{
    const SOURCE_REQUEST_NAME = 'source_request';
    const DESTINATION_REQUEST_NAME = 'destination_request';

    /**
     * @var IntegratorService
     */
    protected $integratorService;

    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * @var SourceRequestRepository
     */
    protected $sourceRequestRepository;

    /**
     * IntegratorController constructor.
     * @param IntegratorService $integratorService
     * @param SearchService|null $searchService
     */
    public function __construct(IntegratorService $integratorService, SearchService $searchService, SourceRequestRepository $sourceRequestRepository)
    {
        $this->integratorService = $integratorService;
        $this->searchService = $searchService;
        $this->sourceRequestRepository = $sourceRequestRepository;
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

    /**
     * @param Request $request
     * @param $sourceRequestId
     * @return JsonResponse
     */
    public function retryIntegrateAction(Request $request, $sourceRequestId)
    {
        try {
            $this->integratorService->retryIntegrate($sourceRequestId);

            return new JsonResponse([
                'msg' => 'Ok! The request will be resent.',
            ], 200);
        } catch (\Error $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @param $sourceRequestId
     */
    public function cancelRequestAction(Request $request, $sourceRequestId)
    {
        /**
         * @var \SonnyBlaine\Integrator\Source\Request $sourceRequest
         */
        $sourceRequest = $this->sourceRequestRepository->find($sourceRequestId);

        try {
            $sourceRequest->setCancelled(true);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        }

        $this->sourceRequestRepository->save($sourceRequest);

        return new JsonResponse(['request_id' => $sourceRequestId], 200);
    }
}