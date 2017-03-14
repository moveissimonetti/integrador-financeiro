<?php
namespace SonnyBlaine\Integrator\Source;

use Doctrine\Common\Collections\Collection as DestinationsCollection;
use Doctrine\Common\Collections\ArrayCollection as DestinationRequestsCollection;
use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\Connection;
use SonnyBlaine\Integrator\Destination\Request as DestinationRequest;

/**
 * Class Request
 * @package SonnyBlaine\Integrator\Source
 * @ORM\Entity(repositoryClass="SonnyBlaine\Integrator\Source\RequestRepository")
 * @ORM\Table(name="source_request")
 */
class Request
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
     * Source of Request
     * @ORM\ManyToOne(targetEntity="SonnyBlaine\Integrator\Source\Source")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     * @var Source
     */
    protected $source;

    /**
     * Query Parameter
     * @ORM\Column(type="string", name="query_parameter")
     * @var string
     */
    protected $queryParameter;

    /**
     * Collection of Destination Requests
     * @ORM\OneToMany(targetEntity="SonnyBlaine\Integrator\Destination\Request", mappedBy="sourceRequest")
     * @var DestinationRequestsCollection|DestinationRequest[]
     */
    protected $destinationRequests;

    /**
     * Request constructor.
     * @param Source $source Source of Request
     * @param string $queryParameter Parameter of the Query
     */
    public function __construct(Source $source, string $queryParameter)
    {
        $this->source = $source;
        $this->queryParameter = $queryParameter;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->source->getConnection();
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->source->getSql();
    }

    /**
     * @return Destination[]|DestinationsCollection
     */
    public function getDestinations(): DestinationsCollection
    {
        return $this->source->getDestinations();
    }

    /**
     * @return string
     */
    public function getQueryParameter(): string
    {
        return $this->queryParameter;
    }

    /**
     * @return DestinationRequestsCollection|DestinationRequest[]
     */
    public function getDestinationRequests(): DestinationRequestsCollection
    {
        return $this->destinationRequests;
    }

    /**
     * @return string
     */
    public function getSourceIdentifier(): string
    {
        return $this->source->getIdentifier();
    }
}