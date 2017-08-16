<?php

namespace SonnyBlaine\Integrator\Services;

use SonnyBlaine\Integrator\RequestStatusInterface;
use SonnyBlaine\Integrator\Source\Request as SourceRequest;
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
    public function findByIdentifier(string $identifier, array $filters = []): ?Source
    {
        return $this->sourceRepository->findOneBy(['identifier' => $identifier]);
    }

    /**
     * @param array $filters
     * @return array
     */
    public function search(array $filters)
    {
        return $this->sourceRepository->search($filters);
    }

    public function findToView(array $filters = []) {
        return $this->sourceRepository->findToView($filters);
    }
}