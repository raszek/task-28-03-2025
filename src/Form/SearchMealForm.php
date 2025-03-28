<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;

class SearchMealForm
{

    public function __construct(
        #[Length(max: 255)]
        public ?string $search = null,
        public array $ids = [],
    ) {
    }

}
