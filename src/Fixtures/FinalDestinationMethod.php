<?php
namespace SonnyBlaine\Integrator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SonnyBlaine\Integrator\Destination\Destination;
use SonnyBlaine\Integrator\Destination\Method;

/**
 * Class FinalDestinationMethodFixture
 * @package SonnyBlaine\Integrator\Fixtures
 */
class FinalDestinationMethodFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var $destination Destination
         */
        $destination = $this->getReference('finalDestination');

        /**
         * @var $method Method
         */
        $method = $this->getReference('method');

        $destination->addMethod($method);

        $manager->persist($destination);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 4;
    }
}