<?php

namespace SonnyBlaine\Integrator\Source;

use Doctrine\Common\Collections\Collection as DestinationsCollection;
use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\Connection;

/**
 * Class Source
 * @package SonnyBlaine\Integrator\Source
 * @ORM\Entity(repositoryClass="SonnyBlaine\Integrator\Source\SourceRepository")
 * @ORM\Table(name="source")
 */
class Source
{

    /**
     * Source ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Source Identifier
     * @ORM\Column(type="string", name="identifier")
     * @var string
     */
    protected $identifier;

    /**
     * Data connection to database
     * @ORM\ManyToOne(targetEntity="SonnyBlaine\Integrator\Connection")
     * @ORM\JoinColumn(name="connection_id", referencedColumnName="id")
     * @var Connection
     */
    protected $connection;

    /**
     * Base SQL
     * @ORM\Column(type="text", name="query")
     * @var string
     */
    protected $sql;

    /**
     * Indicates that multiple database records can be used to create multiple requests
     * @ORM\Column(type="boolean", name="is_allowed_multiple_resultset")
     * @var boolean
     */
    protected $isAllowedMultipleResultset;

    /**
     * Indicates that multiple requests can be created with same source and query parameter
     * @ORM\Column(type="boolean", name="is_allowed_multiple_requests")
     * @var boolean
     */
    protected $isAllowedMultipleRequests;

    /**
     * List of destinations
     * @ORM\ManyToMany(targetEntity="SonnyBlaine\Integrator\Source\Destination")
     * @ORM\JoinTable(name="source_destination",
     *     joinColumns={@ORM\JoinColumn(name="source_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="destination_id", referencedColumnName="id")}
     * )
     * @var DestinationsCollection
     */
    protected $destinations;

    /**
     * Source constructor.
     * @param string $identifier
     * @param Connection $connection
     * @param string $sql
     * @param bool $isAllowedMultipleResultset
     * @param bool $isAllowedMultipleRequests
     * @param DestinationsCollection $destinations
     */
    public function __construct(
        string $identifier,
        Connection $connection,
        string $sql,
        bool $isAllowedMultipleResultset,
        bool $isAllowedMultipleRequests,
        DestinationsCollection $destinations
    ) {
        $this->identifier = $identifier;
        $this->connection = $connection;
        $this->sql = $sql;
        $this->isAllowedMultipleResultset = $isAllowedMultipleResultset;
        $this->isAllowedMultipleRequests = $isAllowedMultipleRequests;
        $this->destinations = $destinations;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return boolean
     */
    public function isAllowedMultipleResultset(): bool
    {
        return $this->isAllowedMultipleResultset;
    }

    /**
     * @return boolean
     */
    public function isAllowedMultipleRequests(): bool
    {
        return $this->isAllowedMultipleRequests;
    }

    /**
     * @return DestinationsCollection
     */
    public function getDestinations(): DestinationsCollection
    {
        return $this->destinations;
    }
}