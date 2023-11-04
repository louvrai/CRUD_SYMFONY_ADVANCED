<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i = 0 ; $i < 50 ; $i++) {
            $author = new Author();
            $author->setUsername($faker->name);
            $author->setEmail($faker->email);
            $manager->persist($author);
        }

        $manager->flush();
    }
}
