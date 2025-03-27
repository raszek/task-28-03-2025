<?php

namespace App\Service\MealApi;

use App\Helper\ArrayHelper;
use App\Helper\JsonHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class MealApi
{

    private Client $client;

    public function __construct(
        string $apiKey
    ) {
        $this->client = new Client([
            'base_uri' => sprintf('https://www.themealdb.com/api/json/v1/%s/', $apiKey)
        ]);
    }

    /**
     * @return Category[]
     * @throws GuzzleException|\JsonException
     */
    public function getCategories(): array
    {
        $response = $this->client->request('GET', 'categories.php');

        $result = JsonHelper::decode($response->getBody()->getContents());

        return ArrayHelper::map($result['categories'], fn($item) => new Category(
            id: $item['idCategory'],
            name: $item['strCategory'],
        ));
    }

    /**
     * @param string $categoryName
     * @return string[]
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function getMealIdsByCategory(string $categoryName): array
    {
        $response = $this->client->request('GET', sprintf('filter.php?c=%s', $categoryName));
        
        $result = JsonHelper::decode($response->getBody()->getContents());

        return ArrayHelper::map($result['meals'], fn($item) => $item['idMeal']);
    }


    public function getMealDetails(string $mealId): MealDetails
    {
        $response = $this->client->request('GET', sprintf('lookup.php?i=%s', $mealId));

        $result = JsonHelper::decode($response->getBody()->getContents())['meals'];

        if (!isset($result[0])) {
            throw new RuntimeException('Meal not found');
        }

        $record = $result[0];

        return new MealDetails(
            id: $record['idMeal'],
            title: $record['strMeal'],
            category: $record['strCategory'],
            instructions: $record['strInstructions'],
            thumbnailUrl: $record['strMealThumb'],
            tags: $this->getTags($record['strTags']),
            ingredients: $this->getIngredients($record),
        );
    }

    private function getTags(?string $tags): array
    {
        if (!$tags) {
            return [];
        }

        $splitTags = explode(',', $tags);

        return ArrayHelper::filter($splitTags, fn($splitTag) => $splitTag !== '');
    }

    private function getIngredients(array $result): array
    {
        $ingredients = [];

        foreach (range(1, 20) as $index) {
            if (isset($result['strIngredient' . $index]) && $result['strIngredient' . $index]) {
                $ingredients[] = $result['strIngredient' . $index];
            }
        }

        return $ingredients;
    }

}
