<?php

namespace CTT\CriteriumBundle\Repository;

use Doctrine\ORM\Query;

/**
 * ParticipationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ParticipationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findInscrits()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult(Query::HYDRATE_ARRAY);
        return $results;
    }
}
