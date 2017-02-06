<?php

namespace SonnyBlaine\Integrator\Destination;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Method
 * @package SonnyBlaine\Integrator\Destination
 * @ORM\Entity()
 * @ORM\Table(name="method")
 */
class Method
{
    /**
     * Method ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Method Description
     * @ORM\Column(type="string", name="description")
     * @var string
     */
    protected $description;

    /**
     * Method Identifier
     * @ORM\Column(type="string", name="identifier")
     * @var string
     */
    protected $identifier;

    /**
     * Method Params
     * @var array
     */
    protected $params;

    /**
     * Method constructor.
     * @param string $description
     * @param string $identifier
     * @param array $param
     */
    public function __construct(string $description, string $identifier, array $param)
    {
        $this->description = $description;
        $this->identifier = $identifier;
        $this->params = $param;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}