<?php

namespace App\Factory;

use App\Entity\Meal;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Meal>
 */
final class MealFactory extends PersistentProxyObjectFactory
{

    public static function class(): string
    {
        return Meal::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'category' => self::faker()->text(255),
            'externalId' => self::faker()->numberBetween(1, 9999),
            'instructions' => self::faker()->text(),
            'thumbUrl' => self::faker()->text(255),
            'title' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Meal $meal): void {})
        ;
    }
}
