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
}