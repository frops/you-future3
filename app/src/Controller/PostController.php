<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/latest", name="post_latest", methods="GET")
     */
    public function latest(PostRepository $postRepository): Response
    {
        return $this->render('post/latest.html.twig', ['posts' => $postRepository->findAll()]);
    }

    /**
     * @Route("/{date}/{slug}", name="post_show", methods="GET")
     * @param $date
     * @param $slug
     * @param PostRepository $postRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show($date, $slug, PostRepository $postRepository): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['date' => \DateTime::createFromFormat("Y-m-d", $date), 'slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException("Запись не найдена");
        }

        return $this->render('post/show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/new", name="post_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $post = new Post();
        $post->setDate(new \DateTime('now'));

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash('notice', 'Сохранено');

            return $this->redirectToRoute("post_show", ['date' => $post->getDate(), 'slug' => $post->getSlug()]);
        }

        return $this->render('post_new/index.html.twig', [
            'controller_name' => 'PostNewController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     * @param $date
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($date, $slug, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['date' => \DateTime::createFromFormat("Y-m-d", $date), 'slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException("Запись не найдена");
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute("post_show", ['date' => $post->getDate(), 'slug' => $post->getSlug()]);
        }

        return $this->render('post_new/index.html.twig', [
            'controller_name' => 'PostNewController',
            'form' => $form->createView()
        ]);
    }
}
