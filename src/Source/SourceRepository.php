<?php

namespace SonnyBlaine\Integrator\Source;

use Doctrine\ORM\EntityRepository;

/**
 * Class SourceRepository
 * @package SonnyBlaine\Integrator\Source
 */
class SourceRepository extends EntityRepository
{
    /**
     * @param array $params
     * @return array
     */
    public function search(array $params)
    {
        $query = $this->createQueryBuilder('src')->where('1=1');

        if (!empty($params['description'])) {
            $query->andWhere("src.description LIKE :description")
                ->setParameter(":description", "%{$params['description']}%");
        }

        if (!empty($params['identifier'])) {
            $query->andWhere("src.identifier LIKE :identifier")
                ->setParameter(":identifier", "%{$params['identifier']}%");
        }

        return $query->getQuery()->getResult();
    }

    public function findToView(array $filters = [])
    {
        $params = [];

        $where = "";
        if (!empty($filters['description'])) {
            $where .= " AND description LIKE :description";
            $params[':description'] = $filters['description'];
        }
        if (!empty($filters['identifier'])) {
            $where .= " AND identifier LIKE :identifier";
            $params[':identifier'] = $filters['identifier'];
        }

        $sql = "
            SELECT
              s.id,
              s.identifier,
              s.description,
              SUM(sr.success = TRUE) + IFNULL(SUM(dr.success = TRUE), 0) AS request_success,
              SUM(sr.success = FALSE AND sr.try_count = 20) + IFNULL(SUM(dr.success = FALSE AND dr.try_count = 20), 0) AS request_error,
              SUM(sr.success = FALSE AND sr.try_count < 20) + IFNULL(SUM(dr.success = FALSE AND dr.try_count < 20), 0) AS request_pending
            FROM
              source s
              LEFT JOIN source_request sr
                ON sr.source_id = s.id
              LEFT JOIN destination_request dr
                ON dr.source_request_id = sr.id
            WHERE 
              1 = 1
              $where
            GROUP BY s.id;
        ";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        foreach ($params as $k => $v) {
            $v = "%$v%";

            $statement->bindValue($k, $v);
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}