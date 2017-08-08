<?php

namespace SonnyBlaine\Integrator\Source;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use SonnyBlaine\Integrator\Rabbit\RequestCreatorConsumer;

/**
 * Class RequestRepository
 * @package SonnyBlaine\Integrator\Source
 */
class RequestRepository extends EntityRepository
{
    const SEARCH_USING_SOURCE_REQUEST = 'source_request';
    const SEARCH_USING_DESTINATION_REQUEST = 'destination_request';

    /**
     * @param Request $request
     * @return void
     */
    public function save(Request $request): void
    {
        $this->_em->persist($request);
        $this->_em->flush();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $entityAlias
     * @param array $filters
     * @return array
     * @throws \Exception
     */
    private function applySearchFiltersAndExecute(QueryBuilder $queryBuilder, string $entityAlias, array $filters)
    {
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

        if (empty($filters['status'])) {
            return $queryBuilder->getQuery()->getResult();
        }

        switch ($filters['status']) {
            case 'pending':
                $queryBuilder
                    ->andWhere("($entityAlias.success = false AND $entityAlias.tryCount < :try_count)")
                    ->setParameter(":try_count", RequestCreatorConsumer::MAX_TRY_COUNT);
                break;

            case 'error':
                $queryBuilder
                    ->andWhere("($entityAlias.success = false AND $entityAlias.tryCount = :try_count)")
                    ->setParameter(":try_count", RequestCreatorConsumer::MAX_TRY_COUNT);
                break;

            case 'success':
                $queryBuilder->andWhere("$entityAlias.success = true");
                break;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Source $source
     * @param array $params
     * @return array
     */
    public function findBySource(Source $source, array $params = [])
    {
        $queryBuilder = $this
            ->createQueryBuilder('sr')
            ->where('sr.source = :source')
            ->setParameter(":source", $source);

        return $this->applySearchFiltersAndExecute($queryBuilder, 'sr', $params);
    }

    /**
     * @param Source $source
     * @param array $params
     * @return array
     */
    public function findBySourceUsingDestinationRequest(Source $source, array $params = [])
    {
        $queryBuilder = $this
            ->createQueryBuilder('sr')
            ->leftJoin(
                \SonnyBlaine\Integrator\Destination\Request::class, 'dr', Join::WITH, "dr.sourceRequest = sr"
            )
            ->where('sr.source = :source')
            ->setParameter(":source", $source);

        return $this->applySearchFiltersAndExecute($queryBuilder, 'dr', $params);
    }
}