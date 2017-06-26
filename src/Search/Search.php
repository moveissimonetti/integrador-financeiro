<?php

namespace SonnyBlaine\Integrator\Search;

class Search
{
    /**
     * @var string
     */
    private $originName;

    /**
     * @var array
     */
    private $result;

    /**
     * Search constructor.
     * @param string $originName
     * @param array $result
     */
    public function __construct($originName, $result)
    {
        $this->originName = $originName;
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getOriginName(): string
    {
        return $this->originName;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}