<?php

namespace App\Controller;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/post")
 */
class BlogPostController extends AbstractController
{
    /**
     * @Route("/", name="blog_post_index", methods={"GET"})
     */
    public function index($category_id = null, BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findBy(['category' => null,]),
        ]);
    }

    /**
     * @Route("/new/{category_id}", name="blog_post_new", methods={"GET","POST"})
     */
    public function new($category_id = null, Request $request, FileUploader $fileUploader): Response
    {
        $blogPost = new BlogPost();
        if ($category_id) {
            /**
             * @var BlogCategory $category
             */
            $category = $this->getDoctrine()->getRepository(BlogCategory::class)->find($category_id);
            $blogPost->setCategory($category);
        }
        $form = $this->createForm(BlogPostType::class, $blogPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['file_input']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $blogPost->setFile($fileName);
            } else {
                $blogPost->setFile('');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('blog_category_show', ['id' => $blogPost->getCategory()->getId(),]);
        }

        return $this->render('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_show", methods={"GET"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogPost $blogPost, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['file_input']->getData();
            $old_filename = false;
            if ($file) {
                $old_filename = $blogPost->getFile();
                $fileName = $fileUploader->upload($file);
                $blogPost->setFile($fileName);
            }
            $this->getDoctrine()->getManager()->flush();
            if ($old_filename) {
                $filesystem->remove($this->getParameter('blog_post_directory') . '/' . $old_filename);
            }
            return $this->redirectToRoute('blog_category_show', ['id' => $blogPost->getCategory()->getId(),]);
        }

        return $this->render('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost, Filesystem $filesystem): Response
    {
        $category_id = $blogPost->getCategory()->getId();
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            $old_filename = $blogPost->getFile();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();
            if($old_filename) {
                $filesystem->remove($this->getParameter('blog_post_directory') . '/' . $old_filename);
            }
        }

        return $this->redirectToRoute('blog_category_show', ['id' => $category_id,]);
    }
}
