<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i=1; $i < 34 ; $i++) { 
            $article = new Article();
            $article->setTitle($faker->sentence)
                    ->setContent($faker->paragraphs($nb = 3, $asText = true))
                    ->setCreatedAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'));
            $manager->persist($article);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
