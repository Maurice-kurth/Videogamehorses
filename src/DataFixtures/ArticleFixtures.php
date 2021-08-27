<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Importer les fausses données de la libraire Faker, localisée en français
        $faker = \Faker\Factory::create('fr_FR');

        //Créer 3 catégories
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());
            //Inscrire dans la bdd
            $manager->persist($category);

            //Créer 4 à 6 articles
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {

                //Instancier un nouvel Article
                $article = new Article();

                //Prendre des faux paragraphes, les rejoindre par des <p>
                $content = '<p>' . join('</p><p>', $faker->paragraphs(4)) . '</p>';
                $introduction = $faker->paragraph(1);

                //Définir les données des entités
                $article->setTitre($faker->sentence())
                    ->setIntroduction($introduction)
                    ->setContenu($content)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setNote($faker->numberBetween(1, 5))
                    ->setImage($faker->ImageUrl());
                //Inscrire dans la bdd

                $manager->persist($article);

                //Créer entre 4 et 10 commentaires
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    $content = '<p>' . $faker->sentence() . '</p>';

                    /**Calcul pour empêcher que le commentaire aie un CreatedAt avant la date de publication de l'article */
                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '-' . $days . ' days'; // -100 days

                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}