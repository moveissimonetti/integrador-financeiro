<?php
namespace SonnyBlaine\Integrator\Tests;

use Doctrine\DBAL\Connection AS DBALConnection;
use SonnyBlaine\Integrator\Connection;
use SonnyBlaine\Integrator\ConnectionManager;

/**
 * Class ConnectionManagerTest
 * @package SonnyBlaine\Integrator\Tests
 */
class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function getConnection(int $connectionId, string $driver = 'pdo_mysql')
    {
        $mockConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockConnection->method('getId')
            ->willReturn($connectionId);

        $mockConnection->method('getDriver')
            ->willReturn($driver);

        return $mockConnection;
    }

    public function testGetConnectionMustReturnCorrectInstance()
    {
        $baseConnection = $this->getConnection(1);

        $connectionManager = new ConnectionManager();
        $connection = $connectionManager->getConnection($baseConnection);

        $this->assertInstanceOf(DBALConnection::class, $connection);
    }

    public function testGetConnectionMustReturnSameConnection()
    {
        $baseConnection1 = $this->getConnection(1);
        $baseConnection2 = $this->getConnection(1);

        $connectionManager = new ConnectionManager();

        $connection1 = $connectionManager->getConnection($baseConnection1);
        $connection2 = $connectionManager->getConnection($baseConnection2);

        $this->assertSame($connection1, $connection2);
    }

    public function testGetConnectionMustReturn2DifferentConnections()
    {
        $baseConnection1 = $this->getConnection(1);
        $baseConnection2 = $this->getConnection(2);

        $connectionManager = new ConnectionManager();

        $connection1 = $connectionManager->getConnection($baseConnection1);
        $connection2 = $connectionManager->getConnection($baseConnection2);

        $this->assertNotSame($connection1, $connection2);
    }
}
