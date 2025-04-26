<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppProductCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category2 = new Category();
        $category2->setCode('cat2');
        $manager->persist($category2);

        $category = new Category();
        $category->setCode('cat1');
        $manager->persist($category);

        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->setName('prod_' . ($i + 1));
            $randomFloat = 1 + mt_rand() / mt_getrandmax() * (100 - 1);
            $product->setPrice(round($randomFloat, 2));
            $manager->persist($product);
            $relation = new ProductCategory();
            $relation->setCategory($category);
            $relation->setProduct($product);
            $manager->persist($relation);
            if ($i % 100 === 0) {
                $relation = new ProductCategory();
                $relation->setCategory($category2);
                $relation->setProduct($product);
                $manager->persist($relation);
            }
        }

        $manager->flush();
    }
}
