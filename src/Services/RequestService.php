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
        $sourceRequest = new SourceRequest($source, $queryParameter);

        $this->sourceRequestRepository->save($sourceRequest);

        return $sourceRequest;
    }

    /**
     * @param SourceRequest $sourceRequest
     * @return array
     * @throws \Exception
     */
    public function createDestinationRequest(SourceRequest $sourceRequest): array
    {
        $requests = $this->destinationRequestCreator->create($sourceRequest);

        if (empty($requests)) {
            throw new \Exception('Destination Requests list is empty.');
        }

        foreach ($requests as $request) {
            $this->destinationRequestRepository->save($request);
        }

        return $requests;
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