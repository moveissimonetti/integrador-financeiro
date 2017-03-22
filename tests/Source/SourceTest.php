<?php
namespace SonnyBlaine\Integrator\Tests\Source;

use Doctrine\Common\Collections\ArrayCollection;
use SonnyBlaine\Integrator\Connection;
use SonnyBlaine\Integrator\Destination\Destination;
use SonnyBlaine\Integrator\Source\Source;

/**
 * Class SourceTest
 * @package SonnyBlaine\Integrator\Tests\Source
 */
class SourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $dbname = 'mydb';
        $user = 'user';
        $password = 'pass';
        $host = 'localhost';
        $port = 8080;
        $driver = 'pdo_mysql';

        $connection = new Connection($dbname, $user, $password, $host, $port, $driver);

        $identifier = '122345421';
        $sql = 'SELECT* FROM user WHERE 1 = 1';
        $isAllowedMultipleResultset = true;
        $isAllowedMultipleRequests = true;

        $mockDestination1 = $this->getMockBuilder(Destination::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockDestination2 = $this->getMockBuilder(Destination::class)
            ->disableOriginalConstructor()
            ->getMock();

        $destinations = [
            $mockDestination1,
            $mockDestination2
        ];

        $source = new Source(
            $identifier,
            $connection,
            $sql,
            $isAllowedMultipleResultset,
            $isAllowedMultipleRequests,
            new ArrayCollection($destinations)
        );

        $this->assertInstanceOf(Source::class, $source);
        $this->assertEquals($identifier, $source->getIdentifier());
        $this->assertEquals($connection, $source->getConnection());
        $this->assertEquals($sql, $source->getSql());
        $this->assertEquals($isAllowedMultipleResultset, $source->isAllowedMultipleResultset());
        $this->assertEquals($isAllowedMultipleRequests, $source->isAllowedMultipleRequests());
        $this->assertContainsOnlyInstancesOf(Destination::class, $source->getDestinations());
    }
}
