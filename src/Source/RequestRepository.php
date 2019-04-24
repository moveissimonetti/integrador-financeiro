<?php

namespace SonnyBlaine\Integrator\Source;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use SonnyBlaine\Integrator\AbstractRequest;
use SonnyBlaine\Integrator\RequestRepositoryInterface;

/**
 * Class RequestRepository
 * @package SonnyBlaine\Integrator\Source
 */
class RequestRepository extends EntityRepository implements RequestRepositoryInterface
{
    const SEARCH_USING_SOURCE_REQUEST = 'source_request';
    const SEARCH_USING_DESTINATION_REQUEST = 'destination_request';

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
        $sourceRequest = parent::find($id, $lockMode, $lockVersion);

        if ($sourceRequest) {
            $this->getEntityManager()->refresh($sourceRequest);
        }

        return $sourceRequest;
    }

    /**
     * @param Source $source
     * @param array $filters
     * @param string $target
     * @return array
     * @throws \Exception
     */
    public function findBySource(Source $source, array $filters = [], $target = self::SEARCH_USING_SOURCE_REQUEST)
    {
        $targets = [
            self::SEARCH_USING_SOURCE_REQUEST => 'sr',
            self::SEARCH_USING_DESTINATION_REQUEST => 'dr'
        ];

        if (!in_array($target, array_keys($targets))) {
            throw new \Exception("Invalid target: {$target}");
        }

        $queryBuilder = $this
            ->createQueryBuilder('sr')
            ->leftJoin(
                \SonnyBlaine\Integrator\Destination\Request::class, 'dr', Join::WITH, "dr.sourceRequest = sr"
            )
            ->where("sr.source = :source")
            ->setParameter(":source", $source)
            ->andWhere('(sr.success = false OR dr.success = false)');

        $entityAlias = $targets[$target];

        #validate interval
        if (empty($params['createdSince']) xor empty($params['createdUntil'])) {
            throw new \Exception("Date interval must be entered with two params: createdSince and createdUntil");
        }

        #apply filters
        if (!empty($filters['createdIn'])) {
            $queryBuilder->andWhere("SUBSTRING($entityAlias.createdIn, 1, 10) = :createdIn")->setParameter(':createdIn',
                $filters['createdIn']);
        }

        if (!empty($filters['createdSince'])) {
            $queryBuilder->andWhere("SUBSTRING($entityAlias.createdIn, 1, 10) BETWEEN :date_start AND :date_end")
                ->setParameter(":date_start", $filters['createdSince'])
                ->setParameter(":date_end", $filters['createdUntil']);
        }

        if (!empty($filters['msg'])) {
            $queryBuilder
                ->andWhere("$entityAlias.msg LIKE :msg")
                ->setParameter(":msg", "%{$filters['msg']}%");
        }

        return $queryBuilder->getQuery()->getResult();
    }
}