<?php

namespace App\Form;

class SearchMealForm
{

    public function __construct(
        public ?string $search = null
    ) {
    }

}
