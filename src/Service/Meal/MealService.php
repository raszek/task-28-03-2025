<?php

namespace App\Service\Meal;

use App\Entity\Meal;
use App\Form\SearchMealForm;
use App\Repository\MealCommentRepository;
use App\Repository\MealRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

readonly class MealService
{

    const COMMENTS_PER_PAGE = 20;

    public function __construct(
        private PaginatorInterface $paginator,
        private MealRepository $mealRepository,
        private MealCommentRepository $mealCommentRepository
    ) {
    }

    public function list(SearchMealForm $filters, int $page): PaginationInterface
    {
        $query = $this->mealRepository->listQuery($filters);

        return $this->paginator->paginate($query, $page);
    }

    public function getComments(Meal $meal, int $page): PaginationInterface
    {
        $query = $this->mealCommentRepository->mealCommentQuery($meal);

        return $this->paginator->paginate($query, $page, self::COMMENTS_PER_PAGE);
    }

}
