<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\MealComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MealComment>
 */
class MealCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealComment::class);
    }

    public function mealCommentQuery(Meal $meal): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('mealComment');

        $queryBuilder
            ->where('mealComment.meal = :meal')
            ->setParameter('meal', $meal)
            ->orderBy('mealComment.createdAt', 'DESC');

        return $queryBuilder;
    }
}
