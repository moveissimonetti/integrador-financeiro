<?php

namespace SonnyBlaine\Integrator\Destination;

use Doctrine\ORM\Mapping as ORM;
use SonnyBlaine\Integrator\DateInterface;
use SonnyBlaine\Integrator\DateTrait;
use SonnyBlaine\Integrator\RequestStatusInterface;
use SonnyBlaine\Integrator\RequestStatusTrait;
use SonnyBlaine\Integrator\ResponseInterface;
use SonnyBlaine\Integrator\ResponseTrait;
use SonnyBlaine\Integrator\Source\Destination;
use SonnyBlaine\Integrator\Source\Request as SourceRequest;
use SonnyBlaine\Integrator\TryCountInterface;
use SonnyBlaine\Integrator\TryCountTrait;
use SonnyBlaine\IntegratorBridge\RequestInterface;

/**
 * Class Request
 * @package SonnyBlaine\Integrator\Destination
 * @ORM\Entity(repositoryClass="SonnyBlaine\Integrator\Destination\RequestRepository")
 * @ORM\Table(name="destination_request", indexes={
 *     @ORM\Index(name="success_idx", columns={"success"}),
 *     @ORM\Index(name="creation_date_idx", columns={"created_in"}),
 *     @ORM\Index(name="success_date_idx", columns={"success_in"})
 * })
 */
class Request implements RequestInterface, TryCountInterface, ResponseInterface, DateInterface, RequestStatusInterface
{
    use DateTrait;
    use TryCountTrait;
    use ResponseTrait;
    use RequestStatusTrait;

    /**
     * Destination Request ID
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     * @var int
     */
    protected $id;

    /**
     * Destination
     * @ORM\ManyToOne(targetEntity="SonnyBlaine\Integrator\Source\Destination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id")
     * @var Destination
     */
    protected $destination;

    /**
     * Source Request
     * @ORM\ManyToOne(targetEntity="SonnyBlaine\Integrator\Source\Request", inversedBy="destinationRequests", cascade={"ALL"})
     * @ORM\JoinColumn(name="source_request_id", referencedColumnName="id")
     * @var SourceRequest
     */
    protected $sourceRequest;

    /**
     * Source Data
     * @ORM\Column(type="object", name="data")
     * @var \stdClass
     */
    protected $data;

    /**
     * Request constructor.
     * @param Destination $destination
     * @param SourceRequest $sourceRequest
     * @param \stdClass $data
     * @param null|DateTrait $createdIn
     */
    public function __construct(
        Destination $destination,
        SourceRequest $sourceRequest,
        \stdClass $data,
        \DateTime $createdIn = null
    ) {
        if (!$createdIn) {
            $createdIn = new \DateTime();
        }

        $this->destination = $destination;
        $this->sourceRequest = $sourceRequest;
        $this->data = $data;
        $this->createdIn = $createdIn;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Destination
     */
    public function getDestination(): Destination
    {
        return $this->destination;
    }

    /**
     * @return SourceRequest
     */
    public function getSourceRequest(): SourceRequest
    {
        return $this->sourceRequest;
    }

    /**
     * @return \stdClass
     */
    public function getData(): \stdClass
    {
        return $this->data;
    }

    /**
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->destination->getMethod();
    }

    /**
     * @return string
     */
    public function getMethodIdentifier(): string
    {
        return $this->destination->getMethod()->getIdentifier();
    }

    /**
     * @return string
     */
    public function getBridge(): string
    {
        return $this->destination->getFinalDestination()->getBridge();
    }

    /**
     * @return string
     */
    public function getDestinationIdentifier(): string
    {
        return $this->destination->getFinalDestination()->getIdentifier();
    }

    /**
     * @return string
     */
    public function getSourceIdentifier(): string
    {
        return $this->sourceRequest->getSourceIdentifier();
    }
}