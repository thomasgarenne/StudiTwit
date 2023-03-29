<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(Request $request, PostRepository $postRepository, ManagerRegistry $managerRegistry): Response
    {
        $search = $request->query->get("search");
        if ($search) {
            $posts = $postRepository->findByTitle($search);
            dd($posts);
        }

        $results = $managerRegistry->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            "results" => $results,
            //"posts" => $posts
        ]);
    }

    #[Route('/post/new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()) {
            $post = new Post();
            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $post->setUser($this->getUser());
                $post->setCreatedAt(new \DateTime());
                $entityManager->persist($post);
                $entityManager->flush();
                return $this->redirectToRoute('app_post');
            }

            return $this->render('post/form.html.twig', [
                "post_form" => $form->createView()
            ]);
        }

        return $this->redirectToRoute("login");
    }

    #[Route('/post/delete/{id<\d+>}', name: 'app_post_delete')]
    public function delete(Post $post, ManagerRegistry $managerRegistry): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser() !== $post->getUser()) {
            $this->addFlash("error", "Vous ne pouvez pas supprimer une publication qui ne vous appartient pas");
            return $this->redirectToRoute('app_post');
        }
        $managerRegistry->getManager()->remove($post);
        $managerRegistry->getManager()->flush();

        return $this->redirectToRoute('app_post');
    }

    #[Route('/post/edit/{id<\d+>}', name: 'app_post_edit')]

    public function update(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser() !== $post->getUser()) {
            $this->addFlash("error", "Vous ne pouvez pas modifier une publication qui ne vous appartient pas");
            return $this->redirectToRoute('app_post');
            //throw new AccessDeniedException("vous ne pouvez pas accéder à cette page");
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_post');
        }
        return $this->render('post/form.html.twig', [
            "post_form" => $form->createView(),
        ]);
    }
}
