<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostNewController extends Controller
{
    /**
     * @Route("/post/new", name="post_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $post = new Post();
        $post->setDate(new \DateTime('now'));

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('date', DateType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Создать'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->render('post_new/index.html.twig', [
            'controller_name' => 'PostNewController',
            'form' => $form->createView()
        ]);
    }
}
