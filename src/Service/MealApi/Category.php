<?php

namespace App\Service\MealApi;

readonly class Category
{

    public function __construct(
        public string $id,
        public string $name,
    ) {
    }

}
