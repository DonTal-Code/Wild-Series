<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture
{
    /*public const ACTORS = [
        'Norman Reedus',
        'Andrew Lincoln',
        'Lauren Cohan',
        'Jeffrey Dean Morgan',
        'Chandler Riggs',
        'Denzel Washington',
        'Henry Cavill'
    ];
*/
    public function load(ObjectManager $manager)
    {
        /*
        foreach (self::ACTORS as $key => $actorName) {
           $actor = new Actor();
            $actor->setName($actorName);
            $manager->persist($actor);
          $this->addReference('actor_' . $key, $actor);

        }*/
        $manager->flush();
    }
}
