<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Submission;
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

        for($i = 0; $i < 50; $i++) {
            $application = (new Application())->setName('Application ' . $i);

            $manager->persist($application);

            for ($j = 0; $j < 50; $j++) {
                $submission = new Submission($application);
                $submission->setData(
                    [
                        'array' => ['item1', 'item2', 'item2'],
                        'hash' => ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'],
                        'boolean' => true,
                        'integer' => 5,
                        'float' => 0.50,
                        'string' => 'Simple text',
                    ]
                );

                $manager->persist($submission);
            }
        }

        $manager->flush();
    }
}
