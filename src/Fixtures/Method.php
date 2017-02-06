<?php
namespace SonnyBlaine\Integrator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SonnyBlaine\Integrator\Destination\Method;

/**
 * Class MethodFixture
 * @package SonnyBlaine\Integrator\Fixtures
 */
class MethodFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $data = [
        [
            'description' => 'Add Pessoa Juridica',
            'identifier' => 'IncluirPessoaJuridica',
        ],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            $method = new Method($data['description'], $data['identifier'], []);

            $manager->persist($method);

            $this->addReference('method', $method);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }
}