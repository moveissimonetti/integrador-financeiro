<?php
namespace SonnyBlaine\Integrator\Services;

use SonnyBlaine\Integrator\Destination\RequestCreator as DestinationRequestCreator;
use SonnyBlaine\Integrator\Source\Request as SourceRequest;
use SonnyBlaine\Integrator\Source\RequestRepository as SourceRequestRepository;
use SonnyBlaine\Integrator\Destination\RequestRepository as DestinationRequestRepository;
use SonnyBlaine\Integrator\Source\Source;

/**
 * Class RequestService
 * @package SonnyBlaine\Integrator\Services
 */
class RequestService
{
    /**
     * @var DestinationRequestCreator Class to create destination requests
     */
    protected $destinationRequestCreator;

    /**
     * @var SourceRequestRepository Repository class for Source Request
     */
    protected $sourceRequestRepository;

    /**
     * @var DestinationRequestRepository Repository class for Destination Request
     */
    protected $destinationRequestRepository;

    /**
     * RequestService constructor.
     * @param DestinationRequestCreator $destinationRequestCreator
     * @param SourceRequestRepository $sourceRequestRepository
     * @param DestinationRequestRepository $destinationRequestRepository
     */
    public function __construct(
        DestinationRequestCreator $destinationRequestCreator,
        SourceRequestRepository $sourceRequestRepository,
        DestinationRequestRepository $destinationRequestRepository
    ) {
        $this->destinationRequestCreator = $destinationRequestCreator;
        $this->sourceRequestRepository = $sourceRequestRepository;
        $this->destinationRequestRepository = $destinationRequestRepository;
    }

    /**
     * @param Source $source
     * @param string $queryParameter
     * @return SourceRequest
     */
    public function createSourceRequest(Source $source, string $queryParameter): SourceRequest
    {
        if (!$source->isAllowedMultipleRequests()) {
            /**
             * @var $request SourceRequest
             */
            $request = $this->sourceRequestRepository->findOneBy([
                'source' => $source,
                'queryParameter' => $queryParameter,
            ]);

            if ($request) {
                return $request;
            }
        }

        $sourceRequest = new SourceRequest($source, $queryParameter);

        $this->sourceRequestRepository->save($sourceRequest);

        return $sourceRequest;
    }

    /**
     * @param SourceRequest $sourceRequest
     * @throws \Exception
     * @return void
     */
    public function createDestinationRequest(SourceRequest $sourceRequest): void
    {
        $this->destinationRequestCreator->create($sourceRequest);

        if (empty($sourceRequest->getDestinationRequests()->count())) {
            throw new \Exception('Destination Requests list is empty.');
        }

        $this->sourceRequestRepository->save($sourceRequest);
    }

    /**
     * @param int $sourceRequestId Source Request ID
     * @return null|SourceRequest
     */
    public function findSourceRequest(int $sourceRequestId): ?SourceRequest
    {
        return $this->sourceRequestRepository->find($sourceRequestId);
    }
}