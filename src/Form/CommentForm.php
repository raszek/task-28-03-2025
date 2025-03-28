<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentForm
{

    public function __construct(
        #[NotBlank]
        #[Length(max: 2048)]
        public ?string $content = null
    ) {
    }

}
