<?php
namespace SonnyBlaine\Integrator\Tests\Source;

use SonnyBlaine\Integrator\Destination\Destination as FinalDestination;
use SonnyBlaine\Integrator\Destination\Method;
use SonnyBlaine\Integrator\Source\Destination as SourceDestination;

/**
 * Class DestinationTest
 * @package SonnyBlaine\Integrator\Tests\Source
 */
class DestinationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FinalDestination
     */
    protected $finalDestination;

    /**
     * @var Method
     */
    protected $method;

    /**
     * @var SourceDestination\DataMapping
     */
    protected $dataMapping;

    public function setUp()
    {
        $this->finalDestination = $this->getFinalDestination();
        $this->method = $this->getMethod();
        $this->dataMapping = $this->getDataMapping();
    }

    /**
     * @return FinalDestination
     */
    protected function getFinalDestination()
    {
        $identifier = 'test';
        $name = 'test';
        $bridge = 'test';

        return new FinalDestination($identifier, $name, $bridge);
    }

    protected function getMethod()
    {
        $description = 'Test';
        $identifier = 'test';
        $param = [
            'column1' => 'value1',
            'column2' => 'value2',
            'column3' => 'value3',
        ];

        return new Method($description, $identifier, $param);
    }

    /**
     * @return SourceDestination\DataMapping
     */
    protected function getDataMapping()
    {
        $columns = [
            'oldKey1' => 'newKey1',
            'oldKey2' => 'newKey2',
            'oldKey3' => 'newKey3',
        ];

        return new SourceDestination\DataMapping($columns);
    }

    /**
     * @return SourceDestination
     */
    protected function getSourceDestination()
    {
        return new SourceDestination($this->finalDestination, $this->getMethod(), $this->dataMapping);
    }

    public function testConstructor()
    {
        $sourceDestination = $this->getSourceDestination();

        $this->assertEquals($this->finalDestination, $sourceDestination->getFinalDestination());
        $this->assertInstanceOf(FinalDestination::class, $sourceDestination->getFinalDestination());
        $this->assertEquals($this->method, $sourceDestination->getMethod());
        $this->assertInstanceOf(Method::class, $sourceDestination->getMethod());
        $this->assertEquals($this->dataMapping, $sourceDestination->getDataMapping());
        $this->assertInstanceOf(SourceDestination\DataMapping::class, $sourceDestination->getDataMapping());
    }

    public function testDestinationDataMappingMustReturnCorrectColumn()
    {
        $sourceDestination = $this->getSourceDestination();

        $key = 'oldKey3';
        $expectedColumn = 'newKey3';

        $this->assertEquals($expectedColumn, $sourceDestination->getColumnByKey($key));
    }

    public function testDestinationDataMappingMustReturnNull()
    {
        $sourceDestination = $this->getSourceDestination();

        $key = 'someDifferentKey';
        $expectedColumn = 'newKey3';

        $receivedColumn = $sourceDestination->getColumnByKey($key);

        $this->assertNotEquals($expectedColumn, $receivedColumn);
        $this->assertNull($receivedColumn);
    }
}
