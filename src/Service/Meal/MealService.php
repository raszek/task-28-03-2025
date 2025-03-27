<?php

namespace App\Service\Meal;

use App\Form\SearchMealForm;
use App\Repository\MealRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

readonly class MealService
{

    public function __construct(
        private PaginatorInterface $paginator,
        private MealRepository $mealRepository
    ) {
    }

    public function list(?SearchMealForm $filters, int $page): PaginationInterface
    {
        $query = $this->mealRepository->listQuery($filters);

        return $this->paginator->paginate($query, $page);
    }

}
