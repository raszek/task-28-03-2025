<?php

namespace App\Tests\Controller;


use App\Factory\MealFactory;

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

        $client->submit($form, [
            'search_meal[search]' => 'teriyaki'
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertTrue(str_contains($crawler->text(), 'Teriyaki Chicken Casserole'));
        $this->assertFalse(str_contains($crawler->text(), 'Beef Asado'));
    }
}
