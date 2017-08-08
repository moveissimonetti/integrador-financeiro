<?php

namespace SonnyBlaine\Integrator\Controllers;

use SonnyBlaine\Integrator\RequestStatusInterface;
use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Services\SourceService;
use SonnyBlaine\Integrator\Source\Source;
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
        $data = array_map(function (Source $source) {
            /**
             * @var SourceRequest[] $requests
             */
            $requests = $this->requestService->findSourceRequestsBySource($source);

            $reduce = function ($last, RequestStatusInterface $request) use (&$reduce) {
                $last[$request->getStatus()] += 1;

                if (!($request instanceof SourceRequest)) {
                    return $last;
                }

                return array_reduce($request->getDestinationRequests()->toArray(), $reduce, $last);
            };

            $requestStatus = array_reduce($requests, $reduce, [
                RequestStatusInterface::STATUS_SUCCESS => 0,
                RequestStatusInterface::STATUS_ERROR => 0,
                RequestStatusInterface::STATUS_PENDING => 0
            ]);

            return [
                'identifier' => $source->getIdentifier(),
                'description' => utf8_encode($source->getDescription()),
                'requests' => $requestStatus
            ];
        }, $this->sourceService->search($_REQUEST));

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

            $mapRequest = function ($request, callable $successCallback) {
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
                    $return['msg'] = $request->getMsg();
                }
                if (method_exists($request, 'isSuccess') && $request->isSuccess()) {
                    $return = array_merge($return, $successCallback($request));
                }

                return $return;
            };

            $mapDestinationRequest = function (DestinationRequest $request) use ($mapRequest) {
                return $mapRequest($request, function (DestinationRequest $request) {
                    return [
                        'successIn' => $request->getSuccessIn()
                    ];
                });
            };

            $mapSourceRequest = function (SourceRequest $request) use ($mapRequest, $mapDestinationRequest) {
                return $mapRequest($request, function (SourceRequest $request) use ($mapDestinationRequest) {
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

            return (new JsonResponse($data))
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