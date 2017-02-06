<?php
namespace SonnyBlaine\Integrator\Source;

use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\Destination\Destination as FinalDestination;
use SonnyBlaine\Integrator\Destination\Method;
use SonnyBlaine\Integrator\Source\Destination\DataMapping;

/**
 * Class Destination
 * @package SonnyBlaine\Integrator\Source
 * @ORM\Entity()
 * @ORM\Table(name="destination")
 */
class Destination
{
    /**
     * Destination ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Final destination
     * @ORM\OneToOne(targetEntity="SonnyBlaine\Integrator\Destination\Destination")
     * @ORM\JoinColumn(name="final_destination_id", referencedColumnName="id")
     * @var FinalDestination
     */
    protected $finalDestination;

    /**
     * Destination method
     * @ORM\OneToOne(targetEntity="SonnyBlaine\Integrator\Destination\Method")
     * @ORM\JoinColumn(name="method_id", referencedColumnName="id")
     * @var Method
     */
    protected $method;

    /**
     * Data mapping
     * @ORM\Embedded(class="SonnyBlaine\Integrator\Source\Destination\DataMapping", columnPrefix=false)
     * @var DataMapping
     */
    protected $dataMapping;

    /**
     * Destination constructor.
     * @param FinalDestination $finalDestination
     * @param Method $method
     * @param DataMapping $dataMapping
     */
    public function __construct(FinalDestination $finalDestination, Method $method, DataMapping $dataMapping)
    {
        $this->finalDestination = $finalDestination;
        $this->method = $method;
        $this->dataMapping = $dataMapping;
    }

    /**
     * @return FinalDestination
     */
    public function getFinalDestination(): FinalDestination
    {
        return $this->finalDestination;
    }

    /**
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->method;
    }

    /**
     * @return DataMapping
     */
    public function getDataMapping(): DataMapping
    {
        return $this->dataMapping;
    }

    /**
     * @param string $key Key to use in data mapping
     * @return string
     */
    public function getColumnByKey(string $key)
    {
        return $this->dataMapping->getColumnByKey($key);
    }
}