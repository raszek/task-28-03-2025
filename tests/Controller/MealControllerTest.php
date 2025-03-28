<?php

namespace App\Tests\Controller;


use App\Factory\MealFactory;
use App\Factory\MealIngredientFactory;
use App\Factory\TagFactory;
use App\Repository\MealCommentRepository;

class MealControllerTest extends WebTestCase
{

    /** @test */
    public function user_can_see_meal_list()
    {
        $client = static::createClient();
        $client->followRedirects();

        MealFactory::createOne([
            'title' => 'Teriyaki Chicken Casserole'
        ]);

        MealFactory::createOne([
            'title' => 'Beef Asado'
        ]);

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertTrue(str_contains($crawler->text(), 'Teriyaki Chicken Casserole'));
        $this->assertTrue(str_contains($crawler->text(), 'Beef Asado'));
    }

    /** @test */
    public function user_can_search_meals()
    {
        $client = static::createClient();
        $client->followRedirects();

        MealFactory::createOne([
            'title' => 'Teriyaki Chicken Casserole'
        ]);

        MealFactory::createOne([
            'title' => 'Beef Asado'
        ]);

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Search')->form();

        $submitCrawler = $client->submit($form, [
            'search_meal[search]' => 'teriyaki'
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertTrue(str_contains($submitCrawler->text(), 'Teriyaki Chicken Casserole'));
        $this->assertFalse(str_contains($submitCrawler->text(), 'Beef Asado'));
    }

    /** @test */
    public function user_can_view_meal_details()
    {
        $client = static::createClient();
        $client->followRedirects();

        $meatTag = TagFactory::createOne([
            'name' => 'Meat',
        ]);

        $pieTag = TagFactory::createOne([
            'name' => 'Pie',
        ]);

        $meal = MealFactory::createOne([
            'title' => 'Teriyaki Chicken Casserole',
            'category' => 'Chicken',
            'instructions' => 'Some instructions',
            'tags' => [$meatTag, $pieTag]
        ]);

        MealIngredientFactory::createOne([
            'name' => 'Beef',
            'meal' => $meal,
        ]);

        MealIngredientFactory::createOne([
            'name' => 'Plain Flour',
            'meal' => $meal,
        ]);

        $crawler = $client->request('GET', '/meals/'.$meal->getId());

        $this->assertResponseIsSuccessful();

        $this->assertTrue(str_contains($crawler->text(), 'Teriyaki Chicken Casserole'));
        $this->assertTrue(str_contains($crawler->text(), 'Tags: Meat, Pie'));
        $this->assertTrue(str_contains($crawler->text(), 'Category: Chicken'));
        $this->assertTrue(str_contains($crawler->text(), 'Some instructions'));
        $this->assertTrue(str_contains($crawler->text(), 'Ingredients: Beef, Plain Flour'));
    }

    /** @test */
    public function user_can_add_comment()
    {
        $client = static::createClient();
        $client->followRedirects();

        $meatTag = TagFactory::createOne([
            'name' => 'Meat',
        ]);

        $pieTag = TagFactory::createOne([
            'name' => 'Pie',
        ]);

        $meal = MealFactory::createOne([
            'title' => 'Teriyaki Chicken Casserole',
            'category' => 'Chicken',
            'instructions' => 'Some instructions',
            'tags' => [$meatTag, $pieTag]
        ]);

        MealIngredientFactory::createOne([
            'name' => 'Beef',
            'meal' => $meal,
        ]);

        MealIngredientFactory::createOne([
            'name' => 'Plain Flour',
            'meal' => $meal,
        ]);

        $crawler = $client->request('GET', '/meals/'.$meal->getId());

        $form = $crawler->selectButton('Add comment')->form();

        $submitCrawler = $client->submit($form, [
            'comment[content]' => 'Some comment content'
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertTrue(str_contains($submitCrawler->text(), 'Some comment content'));

        $createdComment = $this->mealCommentRepository()->findOneBy([
            'content' => 'Some comment content',
            'meal' => $meal->getId(),
        ]);

        $this->assertNotNull($createdComment);
    }

    private function mealCommentRepository(): MealCommentRepository
    {
        return $this->getService(MealCommentRepository::class);
    }
}
