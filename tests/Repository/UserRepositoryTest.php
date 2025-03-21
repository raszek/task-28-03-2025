<?php

namespace App\Tests\Repository;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Tests\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    /** @test */
    public function user_can_be_found()
    {
        UserFactory::createOne();

        $this->assertCount(1, $this->userRepository()->findAll());
    }

    private function userRepository(): UserRepository
    {
        return $this->getContainer()->get(UserRepository::class);
    }
}
