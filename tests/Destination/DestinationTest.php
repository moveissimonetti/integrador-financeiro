<?php
namespace SonnyBlaine\Integrator\Tests;

use SonnyBlaine\Integrator\Destination\Destination;

/**
 * Class DestinationTest
 * @package SonnyBlaine\Integrator\Tests
 */
class DestinationTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateProperties()
    {
        $identifier = '122345421';
        $name = 'Rovereti';
        $bridge = 'IncluirPessoaJuridica';

        $destination = new Destination($identifier, $name, $bridge);

        $this->assertInstanceOf(Destination::class, $destination);
        $this->assertEquals($identifier, $destination->getIdentifier());
        $this->assertEquals($name, $destination->getName());
        $this->assertEquals($bridge, $destination->getBridge());
    }
}
