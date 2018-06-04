<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/post/{date}/{slug}", name="post_view")
     * @param string $date
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view($date, $slug)
    {
        $date = \DateTime::createFromFormat("Y-m-d", $date);

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['date' => $date, 'slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException("Нет такой записи");
        }

        return $this->render('post/view.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post
        ]);

    }
}
