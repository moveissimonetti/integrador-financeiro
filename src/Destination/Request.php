<?php
namespace Simonetti\IntegradorFinanceiro\Destination;

use Doctrine\ORM\Mapping as ORM;
use Simonetti\IntegradorFinanceiro\Source\Destination;
use Simonetti\IntegradorFinanceiro\Source\Request as SourceRequest;

/**
 * Class Request
 * @package Simonetti\IntegradorFinanceiro\Destination
 * @ORM\Entity(repositoryClass="Simonetti\IntegradorFinanceiro\Destination\RequestRepository")
 * @ORM\Table(name="destination_request")
 */
class Request
{
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
     * @ORM\ManyToOne(targetEntity="Simonetti\IntegradorFinanceiro\Destination\Destination")
     * @ORM\JoinColumn(name="destination_id", referencedColumnName="id")
     * @var Destination
     */
    protected $destination;

    /**
     * Source Request
     * @ORM\ManyToOne(targetEntity="Simonetti\IntegradorFinanceiro\Source\Request", inversedBy="destinationRequests")
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
     */
    public function __construct(Destination $destination, SourceRequest $sourceRequest, \stdClass $data)
    {
        $this->destination = $destination;
        $this->sourceRequest = $sourceRequest;
        $this->data = $data;
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
    public function getBridge(): string
    {
        return $this->destination->getFinalDestination()->getBridge();
    }

    /**
     * @return string
     */
    public function getDestinationIdenfier(): string
    {
        return $this->destination->getFinalDestination()->getIdentifier();
    }
}