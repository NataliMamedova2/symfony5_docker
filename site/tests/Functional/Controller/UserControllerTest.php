<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory;
use Faker\Generator;

class UserControllerTest extends WebTestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testAddUserSuccess(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/users',
            [
                'username' => $this->faker->userName(),
                'email' => $this->faker->email(),
                'password' => $this->faker->password(10, 45)
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateUserSuccess(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/users/1',
            [
                'username' => $this->faker->userName(),
                'email' => $this->faker->email()
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
