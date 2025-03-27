<?php

namespace App\Service\MealApi;

readonly class MealDetails
{
    public function __construct(
        public string $id,
        public string $title,
        public string $category,
        public string $instructions,
        public string $thumbnailUrl,
        public array $tags,
        public array $ingredients,
    ) {
    }

}
