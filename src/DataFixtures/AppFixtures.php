<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\User;
use App\Factory\ActorFactory;
use App\Factory\CategoryFactory;
use App\Factory\EpisodeFactory;
use App\Factory\ProgramFactory;
use App\Factory\SeasonFactory;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;
    private $encoder;

    public const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',

    ];

    public const ACTORS = [
        'Norman Reedus',
        'Andrew Lincoln',
        'Lauren Cohan',
        'Jeffrey Dean Morgan',
        'Chandler Riggs',
        'Denzel Washington',
        'Henry Cavill',
    ];

    public const IMAGES =
        [
            'public/uploads/logoljmbwhite-60d7414a7869b172061570.png',
            'public/uploads/staffiepuppy.jpeg',
            'public/uploads/staffiepuppyblack.jpeg',
            'public/uploads/staffiepuppybleu.jpeg'

        ];

    public function __construct(Slugify $slugify, UserPasswordEncoderInterface $encoder)
    {
        $this->slugify = $slugify;
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = FACTORY::create();
        $categories = [];
        $actors = [];
        $programs = [];
        $seasons = [];
        $users = [];
        $year = 2010;
        $number = 1;


        for ($i = 0; $i < 6; $i++) {
            $category = new Category();
            $category->setName($faker->word());

            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < 6; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());

            $manager->persist($actor);
            $actors[] = $actor;
        }
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $contributor = new User();
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword(
            $this->encoder->encodePassword(
                $contributor,
                'contributorpassword'
            )
        );

        $manager->persist($contributor);
        $users[] = $contributor;
        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->encoder->encodePassword(
                $admin,
                'adminpassword'
            )
        );

        $manager->persist($admin);
        $users[] = $admin;
        $manager->flush();

        for ($i = 0; $i < 6; $i++) {
            $program = new Program();
            $program->setTitle($faker->name);
            $program->setSummary($faker->text);


            $slug = $this->slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $program->setCategory($faker->randomElement($categories));
            $program->addActor($faker->randomElement($actors));
            $program->setOwner($faker->randomElement($users));

            $manager->persist($program);
            $programs[] = $program;
        }

        for ($i = 0; $i < 6; $i++) {
            $season = new Season();
            $season->setYear($year + $i);
            $season->setNumber($number + $i);
            $season->setDescription($faker->text);
            $season->setProgram($faker->randomElement($programs));

            $manager->persist($season);
            $seasons[] = $season;
        }

        for ($i = 0; $i < 6; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->name);
            $episode->setNumber($number + $i);
            $episode->setSynopsis($faker->paragraph);
            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $episode->setSeason($faker->randomElement($seasons));
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


