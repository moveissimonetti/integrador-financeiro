<?php
namespace SonnyBlaine\Integrator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SonnyBlaine\Integrator\Connection;

/**
 * Class ConnectionFixture
 * @package SonnyBlaine\Integrator\Fixtures
 */
class ConnectionFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $data = [
        [
            'dbname' => 'test',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'port' => 3306,
            'driver' => 'pdo_mysql',
        ],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            $connection = new Connection(
                $data['dbname'],
                $data['user'],
                $data['password'],
                $data['host'],
                $data['port'],
                $data['driver']
            );

            $manager->persist($connection);

            $this->addReference('connection', $connection);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}