<?php

namespace App\Service\Meal\MealEditor;

use App\Entity\Meal;
use App\Entity\MealComment;
use App\Form\CommentForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;

readonly class MealEditor
{
    public function __construct(
        private Meal $meal,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock
    ) {
    }

    public function addComment(CommentForm $form): void
    {
        $mealComment = new MealComment(
            username: $form->username,
            content: $form->content,
            createdAt: $this->clock->now(),
            meal: $this->meal
        );

        $this->entityManager->persist($mealComment);

        $this->meal->addMealComment($mealComment);

        $this->entityManager->flush();
    }
}
