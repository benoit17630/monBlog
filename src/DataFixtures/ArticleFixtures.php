<?php

namespace App\DataFixtures;

use App\Entity\Admin\Article;

use App\Entity\Admin\Category;
use App\Entity\Admin\Color;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($j =0; $j<5; $j++) {
            for ($k = 1; $k <= 1; $k++) {


                $color = new Color();
                $color->setName($faker->colorName)
                      ->setColor($faker->hexColor);
                $manager->persist($color);
            }
            $category = new Category();
            $category
                ->setName($faker->words(3, true))
                ->setColor($color);

                for ($i = 0 ; $i < 20 ; $i++){


                    $article = new Article();
                    $article
                        ->setName($faker->words(3,true))
                        ->setContent($faker->text("255"))
                        ->setCreatedAt($faker->datetime("now",'utc'))
                        ->setIsPublished($faker->boolean)

                        ->setImage($faker->imageUrl($width = 640, $height = 480))
                        ->setCategory($category)

                    ;


                    $manager->persist($article);
                }
        $manager->persist($category);
    }


        $manager->flush();
    }
}
