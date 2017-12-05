<?php

namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\AbstractRequest;
use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Services\SourceService;
use SonnyBlaine\Integrator\Source\Request as SourceRequest;
use SonnyBlaine\Integrator\Destination\Request as DestinationRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SourceController
{
    /**
     * @var SourceService
     */
    private $sourceService;

    /**
     * @var Request
     */
    private $requestService;

    /**
     * SourceController constructor.
     * @param SourceService $sourceService
     * @param RequestService $requestService
     */
    function __construct(SourceService $sourceService, RequestService $requestService)
    {
        $this->sourceService = $sourceService;
        $this->requestService = $requestService;
    }

    /**
     * @return JsonResponse
     */
    public function searchAction()
    {
        $data = array_map(function (array $data) {
            return [
                "identifier" => utf8_encode($data['identifier']),
                "description" => utf8_encode($data['description']),
                "requests" => [
                    "success" => intval($data['request_success']),
                    "error" => intval($data['request_error']),
                    "pending" => intval($data['request_pending'])
                ]
            ];
        }, $this->sourceService->findToView($_REQUEST));

        return (new JsonResponse($data))
            ->setEncodingOptions(JSON_PRETTY_PRINT);
    }

    /**
     * @param Request $request
     * @param string $sourceIdentifier
     * @return JsonResponse
     */
    public function fetchRequestsAction(Request $request, string $sourceIdentifier)
    {
        try {
            $source = $this->sourceService->findByIdentifier($sourceIdentifier);
            if (!$source) {
                throw new \Exception('Source Not Found', 404);
            }

            $mapRequest = function (AbstractRequest $request, callable $callback) {
                if ($request->isCancelled()) {
                    return null;
                }

                $return = [];

                if (method_exists($request, 'getId')) {
                    $return['id'] = $request->getId();
                }
                if (method_exists($request, 'getSourceIdentifier')) {
                    $return['sourceIdentifier'] = $request->getSourceIdentifier();
                }
                if (method_exists($request, 'getCreatedIn')) {
                    $return['createdIn'] = $request->getCreatedIn();
                }
                if (method_exists($request, 'getTryCount')) {
                    $return['tryCount'] = $request->getTryCount();
                }
                if (method_exists($request, 'getQueryParameter')) {
                    $return['queryParameter'] = $request->getQueryParameter();
                }
                if (method_exists($request, 'getStatus')) {
                    $return['status'] = $request->getStatus();
                }
                if (method_exists($request, 'getMsg')) {
                    $return['msg'] = utf8_encode($request->getMsg());
                }

                $return = array_merge($return, $callback($request));

                return $return;
            };

            $mapDestinationRequest = function (DestinationRequest $request) use ($mapRequest) {
                return $mapRequest($request, function (DestinationRequest $request) {
                    if (!$request->isSuccess()) {
                        return ['successIn' => null];
                    }

                    return [
                        'successIn' => $request->getSuccessIn()
                    ];
                });
            };

            $mapSourceRequest = function (SourceRequest $request) use ($mapRequest, $mapDestinationRequest) {
                return $mapRequest($request, function (SourceRequest $request) use ($mapDestinationRequest) {
                    if (!$request->isSuccess()) {
                        return ['successIn' => null, 'destinationRequests' => null];
                    }

                    return [
                        'successIn' => $request->getSuccessIn(),
                        'destinationRequests' => array_map(
                            $mapDestinationRequest, $request->getDestinationRequests()->toArray()
                        )
                    ];
                });
            };

            $sourceRequests = $this->requestService->findSourceRequestsBySource($source, $_REQUEST);
            $data = array_map(
                $mapSourceRequest, $sourceRequests
            );

            $srError = $srPending = $drError = $drPending = [];

            foreach ($data as $request) {

                switch ($request['status']) {
                    case SourceRequest::STATUS_ERROR:
                        $srError[] = $request;
                        continue;
                    case SourceRequest::STATUS_PENDING:
                        $srPending[] = $request;
                        continue;
                    case SourceRequest::STATUS_SUCCESS:
                        $pending = false;

                        foreach ($request['destinationRequests'] as $destRequest) {
                            $pending = DestinationRequest::STATUS_PENDING == $destRequest['status'];
                            if (DestinationRequest::STATUS_ERROR == $destRequest['status']) {
                                $drError[] = $request;
                                break;
                            }
                        }

                        if ($pending) {
                            $drPending[] = $request;
                        }
                }
            }

            return (new JsonResponse(array_merge($srError, $drError, $srPending, $drPending)))
                ->setEncodingOptions(JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;

            return new JsonResponse([
                'error' => true,
                'msg' => $e->getMessage()
            ], $code);
        }
    }
}