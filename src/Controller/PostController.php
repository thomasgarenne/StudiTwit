<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', []);
    }

    #[Route('/post/new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($post);
            //$entityManager->flush();
        }

        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView()
        ]);
    }
}
