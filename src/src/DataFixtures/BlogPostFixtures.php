<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogPostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setName("Post {$i}");
            $blogPost->setContent("Content for post {$i}");
            $blogPost->setCategory($this->getReference('BlogCategory-' . (1 + $i % 2)));
            $blogPost->setFile('');
            $manager->persist($blogPost);
            $this->addReference("BlogPost-{$i}", $blogPost);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            BlogCategoryFixtures::class,
        );
    }
}
