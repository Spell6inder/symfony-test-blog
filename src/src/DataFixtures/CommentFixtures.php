<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $i = 1;
        foreach ($this->referenceRepository->getReferences() as $reference_name => $reference) {
            $comment_count = rand(1, 10);
            for ($j = 0; $j < $comment_count; $j++) {
                $comment = new Comment();
                $comment->setAuthor("Author {$i}");
                $comment->setContent("Content {$i}");
                $comment->setCreatedAt(new \DateTime());
                $comment->setThread(
                    strtr(
                        $reference_name,
                        [
                            '-' => '.',
                            'BlogCategory' => 'blog_category',
                            'BlogPost' => 'blog_post',
                        ]
                    )
                );
                $manager->persist($comment);
                $i++;
            }
        }

        $manager->flush();
    }
}
