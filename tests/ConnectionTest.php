<?php
namespace SonnyBlaine\Integrator\Tests;

use SonnyBlaine\Integrator\Connection;

/**
 * Class ConnectionTest
 * @package SonnyBlaine\Integrator\Tests
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
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

        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertEquals($dbname, $connection->getDbname());
        $this->assertEquals($user, $connection->getUser());
        $this->assertEquals($password, $connection->getPassword());
        $this->assertEquals($host, $connection->getHost());
        $this->assertEquals($port, $connection->getPort());
        $this->assertEquals($driver, $connection->getDriver());
    }
}
