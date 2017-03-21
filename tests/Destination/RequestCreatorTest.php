<?php
namespace SonnyBlaine\Integrator\Tests\Destination;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection AS DBALConnection;
use SonnyBlaine\Integrator\ConnectionManager;
use SonnyBlaine\Integrator\Destination\RequestCreator;
use SonnyBlaine\Integrator\Source;
use SonnyBlaine\Integrator\Destination;

/**
 * Class RequestCreatorTest
 * @package SonnyBlaine\Integrator\Tests\Destination
 */
class RequestCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ConnectionManager
     */
    protected $connectionManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Source\Request
     */
    protected $sourceRequest;

    public function setUp()
    {
        $this->connectionManager = $this->getMockBuilder(ConnectionManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sourceRequest = $this->getMockBuilder(Source\Request::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage No records found in the database to compose the request.
     */
    public function testCreateMustThrowExceptionIfNotFoundData()
    {
        $requestCreator = new RequestCreator($this->connectionManager);
        $requestCreator->create($this->sourceRequest);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DBALConnection
     */
    protected function getDbalConnection()
    {
        $dbalConnection = $this->getMockBuilder(DBALConnection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dbalConnection->method('fetchAll')
            ->willReturn([
                [
                    'column1' => 'value1',
                    'column2' => 'value2',
                    'column3' => 'value3',
                ]
            ]);

        return $dbalConnection;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Source\Destination
     */
    protected function getDestination()
    {
        return $this->getMockBuilder(Source\Destination::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
