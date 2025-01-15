<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AboutMeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $aboutMe = new AboutMe();
        $aboutMe->setKey('name');
        $aboutMe->setValue('Karol');
        $manager->persist($aboutMe);

        $aboutMe = new AboutMe();
        $aboutMe->setKey('description');
        $aboutMe->setValue('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus euismod sodales quam aliquam pulvinar. Fusce rhoncus libero vitae quam finibus, in tempus massa rhoncus.');
        $manager->persist($aboutMe);

        $manager->flush();
    }
}
