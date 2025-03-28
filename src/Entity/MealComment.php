<?php

namespace App\Entity;

use App\Repository\MealCommentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealCommentRepository::class)]
class MealComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'mealComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meal $meal = null;

    public function __construct(
        string $content,
        DateTimeImmutable $createdAt,
        Meal $meal
    ) {
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->meal = $meal;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }
}
