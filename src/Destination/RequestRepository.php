<?php

namespace SonnyBlaine\Integrator\Destination;

use Doctrine\ORM\EntityRepository;
use SonnyBlaine\Integrator\AbstractRequest;
use SonnyBlaine\Integrator\RequestRepositoryInterface;

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
        $this->getEntityManager()->persist($request);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int|mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return null|object|Request
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $destinationRequest = parent::find($id, $lockMode, $lockVersion);

        if ($destinationRequest) {
            $this->getEntityManager()->refresh($destinationRequest);
        }

        return $destinationRequest;
    }
}