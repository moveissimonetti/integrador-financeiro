<?php

namespace SonnyBlaine\Integrator\Source;

use Doctrine\Common\Collections\Collection as DestinationsCollection;
use Doctrine\Common\Collections\Collection as DestinationRequestsCollection;
use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\AbstractRequest;
use SonnyBlaine\Integrator\Connection;
use SonnyBlaine\Integrator\Destination\Request as DestinationRequest;

/**
 * Class Request
 * @package SonnyBlaine\Integrator\Source
 * @ORM\Entity(repositoryClass="SonnyBlaine\Integrator\Source\RequestRepository")
 * @ORM\Table(name="source_request", indexes={
 *     @ORM\Index(name="success_idx", columns={"success"}),
 *     @ORM\Index(name="creation_date_idx", columns={"created_in"}),
 *     @ORM\Index(name="success_date_idx", columns={"success_in"})
 * })
 */
class Request extends AbstractRequest
{
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
     * @ORM\OneToMany(targetEntity="SonnyBlaine\Integrator\Destination\Request", mappedBy="sourceRequest", cascade={"ALL"})
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
        parent::__construct();

        $this->source = $source;
        $this->queryParameter = $queryParameter;
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
     * @return null|DestinationRequestsCollection|DestinationRequest[]
     */
    public function getDestinationRequests(): ?DestinationRequestsCollection
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

    /**
     * @param DestinationRequest $request
     */
    public function addDestinationRequest(DestinationRequest $request)
    {
        $this->destinationRequests->add($request);
    }

    /**
     * @return boolean
     */
    public function isAllowedMultipleResultset()
    {
        return $this->source->isAllowedMultipleResultset();
    }

    /**
     * @param bool $cancelled
     */
    public function setCancelled(bool $cancelled)
    {
        parent::setCancelled($cancelled);

        $destinationRequests = $this->destinationRequests->toArray();

        if (empty($destinationRequests)) {
            return;
        }

        /**
         * @var \SonnyBlaine\Integrator\Destination\Request $destinationRequest
         */
        foreach ($destinationRequests as $destinationRequest) {
            $destinationRequest->setCancelled(true);
        }
    }
}