<?php

namespace App\Tests\Service;


use App\Service\MealApi\MealApi;
use App\Tests\KernelTestCase;

class MealApiTest extends KernelTestCase
{

    /**
     * @test
     * @group api
     */
    public function meal_api_can_list_categories()
    {
        $api = $this->create();
        
        $categories = $api->getCategories();

        $this->assertCount(14, $categories);
    }

    /**
     * @test
     * @group api
     */
    public function meal_api_can_get_meal_ids_by_category()
    {
        $api = $this->create();

        $result = $api->getMealIdsByCategory('Seafood');

        $this->assertCount(29, $result);
    }

    /**
     * @test
     * @group api
     */
    public function meal_api_can_fetch_meal_details()
    {
        $api = $this->create();

        $mealDetails = $api->getMealDetails('52772');

        $this->assertEquals('52772', $mealDetails->id);
        $this->assertEquals('Teriyaki Chicken Casserole', $mealDetails->title);
        $this->assertEquals('Chicken', $mealDetails->category);

        $this->assertCount(2, $mealDetails->tags);

        $this->assertCount(9, $mealDetails->ingredients);
    }

    private function create(): MealApi
    {
        return $this->getService(MealApi::class);
    }
}
