<?php

namespace App\Tests\Service\Meal;

use App\Factory\MealFactory;
use App\Factory\TagFactory;
use App\Repository\MealRepository;
use App\Repository\TagRepository;
use App\Service\Meal\MealSync;
use App\Service\MealApi\Category;
use App\Service\MealApi\MealApi;
use App\Service\MealApi\MealDetails;
use App\Tests\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class MealSyncTest extends KernelTestCase
{
    /** @test */
    public function meal_sync_can_fetch_meals_not_existing_in_database()
    {

        $apiMock = new class extends MealApi {

            public function __construct()
            {
            }

            public function getCategories(): array
            {
                return [
                    new Category(id: '1', name: 'Seafood'),
                    new Category(id: '2', name: 'Beef'),
                ];
            }

            public function getMealIdsByCategory(string $categoryName): array
            {
                $mealsByCategory = [
                    'Seafood' => [
                        '1',
                        '2'
                    ],
                    'Beef' => [
                        '3'
                    ]
                ];

                return $mealsByCategory[$categoryName];
            }

            public function getMealDetails(string $mealId): MealDetails
            {
                return new MealDetails(
                    id: $mealId,
                    title: 'Some title',
                    category: 'Category',
                    instructions: 'Some instructions',
                    thumbnailUrl: 'url',
                    tags: ['sometag', 'notexistingtag'],
                    ingredients: ['ingredient1'],
                );
            }
        };

        MealFactory::createOne([
            'externalId' => '1'
        ]);

        MealFactory::createOne([
            'externalId' => '2'
        ]);

        TagFactory::createOne([
            'name' => 'sometag'
        ]);

        $mealSync = new MealSync(
            $apiMock,
            $this->getService(EntityManagerInterface::class),
            $this->getService(MealRepository::class),
            $this->getService(TagRepository::class),
        );

        $mealSync->execute();

        $meals = $this->mealRepository()->findAll();
        $this->assertCount(3, $meals);

        $createdMeal = $this->mealRepository()->findOneBy(['externalId' => 3]);
        $this->assertNotNull($createdMeal);

        $tags = $this->tagRepository()->findAll();
        $this->assertCount(2, $tags);
    }

    private function mealRepository(): MealRepository
    {
        return $this->getService(MealRepository::class);
    }

    private function tagRepository(): TagRepository
    {
        return $this->getService(TagRepository::class);
    }

}
