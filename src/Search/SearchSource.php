<?php

namespace SonnyBlaine\Integrator\Search;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Search
 * @package SonnyBlaine\Integrator\Search
 * @ORM\Entity()
 * @ORM\Table(name="search_source")
 */
class SearchSource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="source_id", type="string", unique=true)
     * @var string
     */
    private $sourceId;

    /**
     * @ORM\Column(name="method_id", type="string")
     * @var string
     */
    private $methodId;

    /**
     * @ORM\Column(name="bridge", type="string")
     * @var string
     */
    private $bridge;

    /**
     * @ORM\Column(name="origin_name", type="string")
     * @var string
     */
    private $originName;

    /**
     * SearchSource constructor.
     * @param $sourceId
     * @param $methodId
     * @param $bridge
     * @param $originName
     */
    public function __construct($sourceId, $methodId, $bridge, $originName)
    {
        $this->sourceId = $sourceId;
        $this->methodId = $methodId;
        $this->bridge = $bridge;
        $this->originName = $originName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceId
     */
    public function setSourceId(string $sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return string
     */
    public function getMethodId(): string
    {
        return $this->methodId;
    }

    /**
     * @param string $methodId
     */
    public function setMethodId(string $methodId)
    {
        $this->methodId = $methodId;
    }

    /**
     * @return string
     */
    public function getBridge(): string
    {
        return $this->bridge;
    }

    /**
     * @param string $bridge
     */
    public function setBridge(string $bridge)
    {
        $this->bridge = $bridge;
    }

    /**
     * @return string
     */
    public function getOriginName(): string
    {
        return $this->originName;
    }

    /**
     * @param string $originName
     */
    public function setOriginName(string $originName)
    {
        $this->originName = $originName;
    }
}
