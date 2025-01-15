<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('karol.bojski@cdv.pl');
        $user->setPassword('$2y$13$EE7KE8zBCuSi9bhXyg0QTeBFMfM17tvegogeCuMzRcSG/wM0LTtbS');

        $manager->persist($user);
        $manager->flush();
    }
}
