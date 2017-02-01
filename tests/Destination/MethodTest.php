<?php
namespace Simonetti\Integrador\Tests;

use SonnyBlaine\Integrator\Destination\Method;

/**
 * Class MethodTest
 * @package Simonetti\Integrador\Tests
 */
class MethodTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateProperties()
    {
        $description = 'IncluirPessoaJuridica';
        $identifier = 12210;
        $param = ['nome' => 'Basilio Ferraz', 'cpf' => 15717815794];

        $method = new Method($description, $identifier, $param);

        $this->assertEquals($description, $method->getDescription());
        $this->assertEquals($identifier, $method->getIdentifier());
        $this->assertEquals($param, $method->getParams());
        $this->assertArrayHasKey('nome', $method->getParams());
        $this->assertArrayHasKey('cpf', $method->getParams());
    }
}
