<?php

namespace App\Service\Meal\MealEditor;

use App\Entity\Meal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;

readonly class MealEditorFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function create(Meal $meal): MealEditor
    {
        return new MealEditor(
            meal: $meal,
            entityManager: $this->entityManager,
            clock: $this->clock,
        );
    }

}
