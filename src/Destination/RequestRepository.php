<?php

namespace SonnyBlaine\Integrator\Destination;

use Doctrine\ORM\EntityRepository;
use SonnyBlaine\Integrator\AbstractRequest;
use SonnyBlaine\Integrator\RequestRepositoryInterface;
use SonnyBlaine\IntegratorBridge\RequestInterface;

/**
 * Class RequestRepository
 * @package SonnyBlaine\Integrator\Destination
 */
class RequestRepository extends EntityRepository implements RequestRepositoryInterface
{
    /**
     * @param Request $request
     * @return void
     */
    public function save(AbstractRequest $request): void
    {
        $this->_em->persist($request);
        $this->_em->flush();
    }
}