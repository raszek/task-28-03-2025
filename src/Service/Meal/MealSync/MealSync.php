<?php

namespace App\Service\Meal\MealSync;

use App\Entity\Meal;
use App\Entity\MealIngredient;
use App\Entity\Tag;
use App\Repository\MealRepository;
use App\Repository\TagRepository;
use App\Service\MealApi\MealApi;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Output\OutputInterface;

class MealSync
{

    private array $cachedTags = [];

    public function __construct(
        private readonly OutputInterface $output,
        private readonly MealApi $mealApi,
        private readonly EntityManagerInterface $entityManager,
        private readonly MealRepository $mealRepository,
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function execute(): void
    {
        $mealIds = $this->getAllMealIds();

        $existingMealIds = $this->mealRepository->findExistingMealIds($mealIds);

        $mealIdsToFetch = array_diff($mealIds, $existingMealIds);

        $overallCount = count($mealIdsToFetch);
        foreach ($mealIdsToFetch as $i => $mealId) {
            $this->output->writeln(sprintf('%d/%d fetched. Fetching id: %s', $i, $overallCount, $mealId));
            $mealDetails = $this->mealApi->getMealDetails($mealId);

            $meal = new Meal(
                title: $mealDetails->title,
                category: $mealDetails->category,
                instructions: $mealDetails->instructions,
                thumbUrl: $mealDetails->thumbnailUrl,
                externalId: $mealDetails->id,
            );

            $this->entityManager->persist($meal);

            foreach ($mealDetails->ingredients as $ingredient) {
                $mealIngredient = new MealIngredient(
                    name: $ingredient,
                    meal: $meal,
                );

                $this->entityManager->persist($mealIngredient);

                $meal->addIngredient($mealIngredient);
            }

            foreach ($mealDetails->tags as $tag) {
                $tag = $this->getTag($tag);

                $meal->addTag($tag);
            }

            if ($i % 10 === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $this->cachedTags = [];
            }

            sleep(2);
        }

        $this->entityManager->flush();
    }

    private function getTag(string $tag): Tag
    {
        if (isset($this->cachedTags[$tag])) {
            return $this->cachedTags[$tag];
        }

        $existingTag = $this->tagRepository->findOneBy([
            'name' => $tag,
        ]);

        if ($existingTag) {
            $this->cachedTags[$tag] = $existingTag;
            return $existingTag;
        }

        $newTag = new Tag($tag);

        $this->cachedTags[$tag] = $newTag;
        $this->entityManager->persist($newTag);

        return $newTag;
    }

    /**
     * @return string[]
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function getAllMealIds(): array
    {
        $categories = $this->mealApi->getCategories();

        $ids = [];
        foreach ($categories as $category) {
            $ids = array_merge($ids, $this->mealApi->getMealIdsByCategory($category->name));
        }

        return $ids;
    }
}
