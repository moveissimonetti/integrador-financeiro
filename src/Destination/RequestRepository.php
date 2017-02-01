<?php
namespace SonnyBlaine\Integrator\Destination;

use Doctrine\ORM\EntityRepository;

/**
 * Class RequestRepository
 * @package SonnyBlaine\Integrator\Destination
 */
class RequestRepository extends EntityRepository
{
    /**
     * @param Request $request
     * @return void
     */
    public function save(Request $request): void
    {
        $this->_em->persist($request);
        $this->_em->flush();
    }
}