<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $application = (new Application())
            ->setName('Default Application');

        $manager->persist($application);
        $manager->flush();
    }
}
