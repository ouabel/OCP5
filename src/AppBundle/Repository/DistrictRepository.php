<?php

namespace AppBundle\Repository;

/**
 * DistrictRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DistrictRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @return District[]
     */
    public function findBySearchQuery(string $query): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

            $queryBuilder
                ->orWhere('p.name LIKE :q')
                ->setParameter('q', '%'.$query.'%')
            ;

        return $queryBuilder
            ->orderBy('p.name', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
