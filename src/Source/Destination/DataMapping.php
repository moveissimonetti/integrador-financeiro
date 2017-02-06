<?php

namespace SonnyBlaine\Integrator\Source\Destination;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DataMapping
 * @package SonnyBlaine\Integrator\Source\Destination
 * @ORM\Embeddable()
 */
class DataMapping
{
    /**
     * Columns to make the systems meet
     * @ORM\Column(type="array", name="columns")
     * @var array
     */
    private $columns;

    /**
     * DataMapping constructor.
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * Informed the key the system makes the comparison
     * @param $key
     * @return string
     */
    public function getColumnByKey($key)
    {
        if (!array_key_exists($key, $this->columns)) {
            return null;
        }

        return $this->columns[$key];
    }

}