<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Form\SearchMealForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function listQuery(?SearchMealForm $form): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('meal');

        if (!$form) {
            return $queryBuilder;
        }

        if ($form->search) {
            $queryBuilder->andWhere('LOWER(meal.title) LIKE :search');
            $queryBuilder->setParameter('search', '%' . mb_strtolower($form->search) . '%');
        }

        if ($form->ids) {
            $queryBuilder->andWhere('meal.id IN (:ids)');
            $queryBuilder->setParameter('ids', $form->ids);
        }

        return $queryBuilder;
    }

}
