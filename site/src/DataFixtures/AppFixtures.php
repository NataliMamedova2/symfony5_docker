<?php

namespace App\DataFixtures;

use App\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('test1');
        $user->setPassword('12345678910');
        $user->setEmail('test1@test.mail');

        $manager->persist($user);
        $manager->flush();
    }
}
