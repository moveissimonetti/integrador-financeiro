<?php
namespace SonnyBlaine\Integrator;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Connection
 * @package SonnyBlaine\Integrator
 * @ORM\Entity()
 * @ORM\Table(name="connection")
 */
class Connection
{
    /**
     * Connection ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Name of the database/schema
     * @var string
     * @ORM\Column(type="string", name="dbname")
     */
    protected $dbname;

    /**
     * Username to use when connecting
     * @ORM\Column(type="string", name="user")
     * @var string
     */
    protected $user;

    /**
     * Password to use when connecting
     * @ORM\Column(type="string", name="password")
     * @var string
     */
    protected $password;

    /**
     * Hostname of the database
     * @ORM\Column(type="string", name="host")
     * @var string
     */
    protected $host;

    /**
     * Port of the database
     * @ORM\Column(type="integer", name="port")
     * @var int
     */
    protected $port;

    /**
     * Driver to use when connecting
     * @ORM\Column(type="string", name="driver")
     * @var string
     */
    protected $driver;

    /**
     * Connection constructor.
     * @param string $dbname Name of the database/schema
     * @param string $user Username to use when connecting
     * @param string $password Password to use when connecting
     * @param string $host Hostname of the database
     * @param int $port Port of the database
     * @param string $driver Driver to use when connecting
     */
    public function __construct(string $dbname, string $user, string $password, string $host, int $port, string $driver)
    {
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->driver = $driver;
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
    public function getDbname(): string
    {
        return $this->dbname;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }
}