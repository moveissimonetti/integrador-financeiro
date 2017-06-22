<?php
namespace SonnyBlaine\Integrator\Search;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Search
 * @package SonnyBlaine\Integrator\Search
 * @ORM\Entity()
 * @ORM\Table(name="search_source", schema="test")
 */
class SearchSource {
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
     * SearchSource constructor.
     * @param int $id
     * @param string $sourceId
     * @param string $methodId
     * @param string $bridge
     */
    public function __construct($id, $sourceId, $methodId, $bridge)
    {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->methodId = $methodId;
        $this->bridge = $bridge;
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
}