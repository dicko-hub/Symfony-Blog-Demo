<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Migrations\Version\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker= \Faker\Factory::create('fr_FR');

            //creer 3 categories fakes
        for($i=0;$i<3;$i++){
            $category=new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            $manager->persist($category);
            
            //creer entre 4 et 6 articles
            for($j=1;$j<=\mt_rand(4,6);$j++){
                    $article = new Article();

                    $content='<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';

                    $article ->setTitle($faker->sentence());
                    $article ->setContent($content);
                    $article ->setImage($faker->imageUrl());
                    $article ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                             ->setCategory($category);

                    $manager->persist($article);
                    
                    //creer entre 4 et 10 commentaire
                    for($k=0;$k<=mt_rand(4,10);$k++){
                        $comment= new Comment();
                        $content='<p>'.join($faker->paragraphs(2),'</p><p>').'</p>';
                        $now= new \DateTime();
                        $interval=$now->diff($article->getCreatedAt());
                        $days=$interval->days;
                        $minimum='-'.$days.' days';

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
