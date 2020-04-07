<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Ajout des catégories
        $categories = array('Famille','Politique','Nature','Culture','Sport','Technologie','Loisir','Economie');
        $faker = \Faker\Factory::create('fr_FR');

        foreach($categories as $cat) {
            $categorie = new Category();
            $categorie->setName($cat);
            $manager->persist($categorie);
            // Ajout des articles
            for ($i=0; $i < 8 ; $i++) { 
                $article = new Article();
                $article->setTitle($faker->sentence)
                        ->setContent($faker->paragraphs($nb = 3, $asText = true))
                        ->setCreatedAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'))
                        ->setCategory($categorie);
                $manager->persist($article);
            }
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
