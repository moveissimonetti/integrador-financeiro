<?php
namespace SonnyBlaine\Integrator\Tests\Services;

use Doctrine\Common\Collections\ArrayCollection;
use SonnyBlaine\Integrator\Services\RequestService;
use SonnyBlaine\Integrator\Source\Request as SourceRequest;
use SonnyBlaine\Integrator\Destination\Request as DestinationRequest;
use SonnyBlaine\Integrator\Source\Request;
use SonnyBlaine\Integrator\Source\RequestRepository as SourceRequestRepository;
use SonnyBlaine\Integrator\Destination\RequestRepository as DestinationRequestRepository;
use SonnyBlaine\Integrator\Destination\RequestCreator as DestinationRequestCreator;
use SonnyBlaine\Integrator\Source\Source;

/**
 * Class RequestServiceTest
 * @package SonnyBlaine\Integrator\Tests\Services
 */
class RequestServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DestinationRequestCreator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $destinationRequestCreator;

    /**
     * @var SourceRequestRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sourceRequestRepository;

    /**
     * @var DestinationRequestRepository
     */
    protected $destinationRequestRepository;

    public function setUp()
    {
        $this->destinationRequestCreator = $this->getMockBuilder(DestinationRequestCreator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sourceRequestRepository = $this->getMockBuilder(SourceRequestRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->destinationRequestRepository = $this->getMockBuilder(DestinationRequestRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreateSourceRequestMustReturnExistingRequestIfAlreadyExists()
    {
        /**
         * @var $source Source
         */
        $source = $this->getMockBuilder(Source::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryParameter = '123';

        $existingRequest = new Request($source, $queryParameter);

        $this->sourceRequestRepository->method('findOneBy')
            ->willReturn($existingRequest);

        $service = $this->getRequestService();
        $sourceRequest = $service->createSourceRequest($source, $queryParameter);

        $this->assertSame($existingRequest, $sourceRequest);
    }

    public function testCreateSourceRequestMustReturnInstanceOfSourceRequest()
    {
        /**
         * @var $source Source
         */
        $source = $this->getMockBuilder(Source::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryParameter = '123';

        $this->sourceRequestRepository->method('findOneBy')
            ->willReturn(null);

        $service = $this->getRequestService();
        $sourceRequest = $service->createSourceRequest($source, $queryParameter);

        $this->assertInstanceOf(SourceRequest::class, $sourceRequest);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Destination Requests list is empty
     */
    public function testCreateDestinationRequestMustThrowExceptionIfNotFoundRequestsToCreate()
    {
        /**
         * @var $sourceRequest SourceRequest|\PHPUnit_Framework_MockObject_MockObject
         */
        $sourceRequest = $this->getMockBuilder(SourceRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sourceRequest->method('getDestinationRequests')
            ->willReturn(new ArrayCollection());

        $service = $this->getRequestService();
        $service->createDestinationRequest($sourceRequest);
    }

    /**
     * @return RequestService
     */
    protected function getRequestService()
    {
        return new RequestService(
            $this->destinationRequestCreator,
            $this->sourceRequestRepository,
            $this->destinationRequestRepository
        );
    }
}