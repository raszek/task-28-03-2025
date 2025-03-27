<?php

namespace App\Entity;

use App\Repository\MealIngridientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealIngridientRepository::class)]
class MealIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'mealIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meal $meal = null;

    public function __construct(
        string $name,
        Meal $meal
    ) {
        $this->name = $name;
        $this->meal = $meal;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }
}
