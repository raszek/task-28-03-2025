<?php

namespace App\Service\Meal\MealSync;

use App\Repository\MealRepository;
use App\Repository\TagRepository;
use App\Service\MealApi\MealApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;

readonly class MealSyncFactory
{

    public function __construct(
        private MealApi $mealApi,
        private EntityManagerInterface $entityManager,
        private MealRepository $mealRepository,
        private TagRepository $tagRepository,
    ) {
    }

    public function create(OutputInterface $output): MealSync
    {
        return new MealSync(
            output: $output,
            mealApi: $this->mealApi,
            entityManager: $this->entityManager,
            mealRepository: $this->mealRepository,
            tagRepository: $this->tagRepository,
        );
    }

}
