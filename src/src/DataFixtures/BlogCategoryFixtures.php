<?php

namespace App\DataFixtures;

use App\Entity\BlogCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $blogCategory = new BlogCategory();
            $blogCategory->setName("Category {$i}");
            $blogCategory->setDescription("Description for Category {$i}");
            $manager->persist($blogCategory);
            $this->addReference("BlogCategory-{$i}", $blogCategory);
        }
        $manager->flush();
    }
}
