<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Commentair;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr-FR');

        for ($i = 1; $i <= 3; ++$i) {
            $category = new Category();

            $category->setTitle($faker->sentence())
                     ->setDesciption($faker->paragraph());

            $manager->persist($category);
            //creer entre 4 et 6 article
            for ($j = 1; $j <= mt_rand(4, 6); ++$j) {
                $article = new Article();

                $content = '<p>'.join($faker->paragraphs(5), '<p></p>').'</p>';
                $article->setTitle($faker->sentence())
                     ->setContent($content)
                     ->setImage($faker->imageUrl($faker->dateTimeBetween('-6months')))
                     ->setCategory($category);

                $manager->persist($article);

                for ($k = 1; $k <= mt_rand(4, 10); ++$k) {
                    $comment = new Commentair();
                    $comment = '<p>'.join($faker->paragraphs(5), '<p></p>').'</p>';
                    $comment->setAuthor($faker->name())
                        ->setDescription($faker->paragraphs(4))
                        ->setArticle($article);
                }
            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
