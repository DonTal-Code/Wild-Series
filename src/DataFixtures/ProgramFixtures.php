<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class ProgramFixtures extends Fixture
{
  private $slugify;

   /* public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }*/

    public function load(ObjectManager $manager)
    {
       /* $program = new Program();
        $program->setTitle('Walking dead');
        $program->setSummary('Des zombies envahissent la terre');

        //slug
        $slug = $this->slugify->generate($program->getTitle());
        $program->setSlug($slug);

        $program->setCategory($this->getReference('category_1'));

        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
        for ($i=0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $manager->persist($program);*/
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ActorFixtures::class,

        ];
    }


}

