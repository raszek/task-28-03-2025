<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instructions = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbUrl = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $externalId = null;

    /**
     * @var Collection<int, MealIngredient>
     */
    #[ORM\OneToMany(targetEntity: MealIngredient::class, mappedBy: 'meal')]
    private Collection $ingredients;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    /**
     * @var Collection<int, MealComment>
     */
    #[ORM\OneToMany(targetEntity: MealComment::class, mappedBy: 'meal')]
    private Collection $comments;

    public function __construct(
        string $title,
        string $category,
        string $instructions,
        string $thumbUrl,
        string $externalId,
    ) {
        $this->title = $title;
        $this->category = $category;
        $this->instructions = $instructions;
        $this->thumbUrl = $thumbUrl;
        $this->externalId = $externalId;
        $this->ingredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function getThumbUrl(): ?string
    {
        return $this->thumbUrl;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @return Collection<int, MealIngredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(MealIngredient $ingredient): void
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }
    }

    public function removeIngredient(MealIngredient $ingredient): void
    {
        $this->ingredients->removeElement($ingredient);
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function showTags(): string
    {
        $tags = $this->getTags()
            ->map(fn (Tag $tag) => $tag->getName())
            ->toArray();

        return implode(', ', $tags);
    }

    public function showIngredients(): string
    {
        $ingredients = $this->getIngredients()
            ->map(fn (MealIngredient $ingredient) => $ingredient->getName())
            ->toArray();

        return implode(', ', $ingredients);
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return Collection<int, MealComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addMealComment(MealComment $mealComment): void
    {
        if (!$this->comments->contains($mealComment)) {
            $this->comments->add($mealComment);
        }
    }

    public function removeComment(MealComment $mealComment): void
    {
        $this->comments->removeElement($mealComment);
    }

}
