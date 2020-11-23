<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationFixtures extends Fixture
{
    public const APP_NAME = 'Default Application';

    public function load(ObjectManager $manager)
    {
        $application = (new Application())
            ->setName(self::APP_NAME);

        $manager->persist($application);
        $manager->flush();
    }
}
