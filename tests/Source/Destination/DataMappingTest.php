<?php
namespace SonnyBlaine\Integrator\Tests;

use SonnyBlaine\Integrator\Source\Destination\DataMapping;

/**
 * Class DataMappingTest
 * @package SonnyBlaine\Integrator\Tests
 */
class DataMappingTest extends \PHPUnit_Framework_TestCase
{

    public function testValidateColumns()
    {
        $columns = [
            'empresa' => 'codIntegracaoEmpresa',
            'storeno' => 'codIntegracaoFilial',
            'nome' => 'nomFantasia'
        ];

        $dataMapping = new DataMapping($columns);

        $key = 'storeno';

        $columByKey = $dataMapping->getColumnByKey($key);

        $this->assertInstanceOf(DataMapping::class, $dataMapping);
        $this->assertEquals('codIntegracaoFilial', $columByKey);
    }
}
