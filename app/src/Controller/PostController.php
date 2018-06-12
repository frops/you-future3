<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Parsedown;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/latest", name="post_latest", methods="GET")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function latest(PostRepository $postRepository): Response
    {
        return $this->render('post/latest.html.twig', ['posts' => $postRepository->getLatest()]);
    }

    /**
     * @Route("/{year}/{month}/{day}/{slug}", name="post_show", methods="GET")
     * @param $year
     * @param $month
     * @param $day
     * @param $slug
     * @param PostRepository $postRepository
     * @return Response
     */
    public function show($year, $month, $day, $slug, PostRepository $postRepository): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['date' => \DateTime::createFromFormat("Y-m-d", "{$year}-{$month}-{$day}"), 'slug' => $slug]);

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

            $date = $post->getDate();
            list($year, $month, $day) = $date->format("Y-m-d");

            return $this->redirectToRoute('post_show', ['year' => $year, 'month' => $month, 'day' => $day, 'slug' => $post->getSlug()]);
        }

        return $this->render('post/new.html.twig', [
            'controller_name' => 'PostNewController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{year}/{month}/{day}/{slug}", name="post_edit")
     * @param $year
     * @param $month
     * @param $day
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($year, $month, $day, $slug, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['date' => \DateTime::createFromFormat("Y-m-d", "{$year}-{$month}-{$day}"), 'slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException("Запись не найдена");
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $date = $post->getDate();
            list($year, $month, $day) = $date->format("Y-m-d");

            return $this->redirectToRoute('post_show', ['year' => $year, 'month' => $month, 'day' => $day, 'slug' => $post->getSlug()]);
        }

        return $this->render('post/edit.html.twig', [
            'controller_name' => 'PostNewController',
            'form' => $form->createView()
        ]);
    }
}
