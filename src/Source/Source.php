<?php

namespace Simonetti\IntegradorFinanceiro\Source;

use Doctrine\Common\Collections\ArrayCollection as DestinationsCollection;
use Doctrine\ORM\Mapping as ORM;
use Simonetti\IntegradorFinanceiro\Connection;

/**
 * Class Source
 * @package Simonetti\IntegradorFinanceiro\Source
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="Simonetti\IntegradorFinanceiro\Connection")
     * @ORM\JoinColumn(name="connection_id", referencedColumnName="id")
     * @var Connection
     */
    protected $connection;

    /**
     * Base SQL
     * @ORM\Column(type="string", name="sql)
     * @var string
     */
    protected $sql;

    /**
     * List of destinations
     * @var DestinationsCollection
     */
    protected $destinations;

    /**
     * Source constructor.
     * @param string $identifier
     * @param Connection $connection
     * @param string $sql
     * @param DestinationsCollection $destinations
     */
    public function __construct(
        string $identifier,
        Connection $connection,
        string $sql,
        DestinationsCollection $destinations
    ) {
        $this->identifier = $identifier;
        $this->connection = $connection;
        $this->sql = $sql;
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
     * @return DestinationsCollection
     */
    public function getDestinations(): DestinationsCollection
    {
        return $this->destinations;
    }
}