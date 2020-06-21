<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{thread}", name="index", methods={"GET"})
     */
    public function index($thread, CommentRepository $commentRepository): JsonResponse
    {
        return $this->json($commentRepository->findByThread($thread));
    }

    /**
     * @Route("/{thread}", name="add", methods={"POST","PUT"})
     */
    public function add(string $thread, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$thread) {
            return $this->json(
                ['errors' => ['Invalid thread!',],],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $comment = new Comment();
        $comment->setThread($thread);
        $comment->setCreatedAt(new \DateTime());
        /**
         * @var CommentType $form
         */
        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($data);
        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->json(['success' => true,], Response::HTTP_CREATED);
        }
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $this->json(
            ['errors' => $errors,],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
