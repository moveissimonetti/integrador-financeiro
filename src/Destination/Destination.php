<?php

namespace SonnyBlaine\Integrator\Destination;

use Doctrine\Common\Collections\Collection as MethodsCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Destination
 * @package SonnyBlaine\Integrator\Destination
 * @ORM\Entity()
 * @ORM\Table(name="final_destination", indexes={
 *     @ORM\Index(name="identifier_idx", columns={"identifier"})
 * })
 */
class Destination
{
    /**
     * Destination ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Destination Identifier
     * @ORM\Column(type="string", name="identifier")
     * @var string
     */
    protected $identifier;

    /**
     * Destination Name
     * @ORM\Column(type="string", name="name")
     * @var string
     */
    protected $name;

    /**
     * Destination Bridge
     * @ORM\Column(type="string", name="bridge")
     * @var string
     */
    protected $bridge;

    /**
     * Destination methods
     * @ORM\ManyToMany(targetEntity="SonnyBlaine\Integrator\Destination\Method")
     * @ORM\JoinTable(name="final_destination_method",
     *     joinColumns={@ORM\JoinColumn(name="final_destination_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="method_id", referencedColumnName="id")}
     * )
     * @var MethodsCollection
     */
    protected $methods;

    /**
     * Destination constructor.
     * @param string $identifier
     * @param string $name
     * @param string $bridge
     */
    public function __construct(string $identifier, string $name, string $bridge)
    {
        $this->identifier = $identifier;
        $this->name = $name;
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
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBridge(): string
    {
        return $this->bridge;
    }

    /**
     * @return MethodsCollection
     */
    public function getMethods(): MethodsCollection
    {
        return $this->methods;
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->methods[] = $method;
    }
}