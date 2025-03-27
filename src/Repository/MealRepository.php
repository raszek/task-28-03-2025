<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meal>
 */
class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    /**
     * @param string[] $ids
     * @return string[]
     */
    public function findExistingMealIds(array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('meal');

        $query = $queryBuilder
            ->select(['meal.externalId'])
            ->where('meal.externalId IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery();
        
        $records = $query->getArrayResult();

        return array_column($records, 'externalId');
    }

}
