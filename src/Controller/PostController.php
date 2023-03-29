<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $results = $managerRegistry->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            "results" => $results
        ]);
    }

    #[Route('/post/new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView()
        ]);
    }

    #[Route('/post/delete/{id<\d+>}', name: 'app_post_delete')]
    public function delete(Post $post, ManagerRegistry $managerRegistry): Response
    {
        $managerRegistry->getManager()->remove($post);
        $managerRegistry->getManager()->flush();

        return $this->redirectToRoute('app_post');
    }

    #[Route('/post/edit/{id<\d+>}', name: 'app_post_edit')]
    public function update(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_post');
        }
        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView()
        ]);
    }
}
