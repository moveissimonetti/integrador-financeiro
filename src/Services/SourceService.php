<?php
namespace SonnyBlaine\Integrator\Services;

use SonnyBlaine\Integrator\Source\Source;
use SonnyBlaine\Integrator\Source\SourceRepository;

/**
 * Class SourceService
 * @package SonnyBlaine\Integrator\Services
 */
class SourceService
{
    /**
     * @var SourceRepository Repository class for Source
     */
    protected $sourceRepository;

    /**
     * SourceService constructor.
     * @param SourceRepository $sourceRepository
     */
    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * Method responsible for searching origin by identifier
     * @param string $identifier
     * @return Source
     */
    public function findByIdentifier(string $identifier): Source
    {
        return $this->sourceRepository->findOneBy(['identifier' => $identifier]);
    }
}