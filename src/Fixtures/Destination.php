<?php
namespace SonnyBlaine\Integrator\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SonnyBlaine\Integrator\Destination\Method;
use SonnyBlaine\Integrator\Source\Destination;
use SonnyBlaine\Integrator\Destination\Destination as FinalDestination;

/**
 * Class DestinationFixture
 * @package SonnyBlaine\Integrator\Fixtures
 */
class DestinationFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $data = [
        [
            'identifier' => 'Rovereti',
            'name' => 'Rovereti ERP',
            'bridge' => 'rovereti',
        ],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            /**
             * @var $finalDestination FinalDestination
             */
            $finalDestination = $this->getReference('finalDestination');

            /**
             * @var $method Method
             */
            $method = $this->getReference('method');

            $dataMapping = new Destination\DataMapping([
                'cod_empresa' => 'CodEmpresa',
                'cnpj' => 'NumCnpj',
                'nome_fantasia' => 'NomFantasia',
                'razao_social' => 'RazaoSocial',
                'inscricao_estadual' => 'NumInscricaoEstadual',
                'inscricao_municipal' => 'NumInscricaoMunicipal',
                'endereco_logradouro' => 'NomLogradouro',
                'endereco_numero' => 'NumLogradouro',
                'endereco_complemento' => 'DscComplemento',
                'endereco_bairro' => 'NomBairro',
                'endereco_municipio' => 'NomLocalidade',
                'endereco_uf' => 'SglUF',
                'endereco_cep' => 'NumCep',
                'sigla_pais' => 'SglPais',
                'ddd' => 'NumDDD',
                'telefone' => 'NumTelefone',
                'email' => 'DscEmail',
                'conta_nome_favorecido' => 'NomFavorecido',
                'conta_cpf_cnpj_favorecido' => 'NumCpfCnpjFavorecido',
                'conta_numero_banco' => 'NumBanco',
                'conta_numero_agencia' => 'NumAgencia',
                'conta_numero_conta_corrente' => 'NumContaCorrente',
                'conta_digito_conta_corrente' => 'NumDigitoContaCorrente',
            ]);

            $destination = new Destination(
                $finalDestination,
                $method,
                $dataMapping
            );

            $manager->persist($destination);

            $this->addReference('destination', $destination);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 5;
    }
}