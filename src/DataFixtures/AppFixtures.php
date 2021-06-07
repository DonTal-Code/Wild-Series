<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Factory\ActorFactory;
use App\Factory\CategoryFactory;
use App\Factory\EpisodeFactory;
use App\Factory\ProgramFactory;
use App\Factory\SeasonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
      $faker = FACTORY::create();

      /*  for ($i = 0; $i < 6; $i++) {
             $category = new Category();
             $category->setName($faker->word());

             $manager->persist($category);
         }

         for ($i = 0; $i < 6; $i++) {
             $actor = new Actor();
             $actor->setName($faker->word());


             $manager->persist($actor);
         }*/

        /*$categories = $manager->getRepository(Category::class)->findAll();
        $actors = $manager->getRepository(Actor::class)->findAll();


        for ($i = 0; $i < 6; $i++) {
            $program = new Program();
            $program->setTitle($faker->name);
            $program->setSummary($faker->text);
            $program->setPoster($faker->imageUrl($width = 400, $height = 260));
            $program->setCategory($categories[array_rand($categories)]);
            $program->addActor($actors[array_rand($actors)]);

            $manager->persist($program);

        }*/

        /*$programs = $manager->getRepository(Program::class)->findAll();

        for ($i = 0; $i < 6; $i++) {
            $season = new Season();
            $season->setYear($faker->year);
            $season->setNumber($faker->numberBetween(1, 15));
            $season->setDescription($faker->text);
            $season->setProgram($programs[array_rand($programs)]);
            $manager->persist($season);
        }*/

       $seasons = $manager->getRepository(Season::class)->findAll();

        for ($i = 0; $i < 6; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->name);
            $episode->setNumber($faker->numberBetween(1, 15));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($seasons[array_rand($seasons)]);
            $manager->persist($episode);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}


