<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\ContactSentEvent;
use App\Event\UserCreatedEvent;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/new', name: 'app_user_new')]
    public function new(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): Response {

        $user = new User($passwordHasher);
        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            //On crée l'objet event
            $event = new UserCreatedEvent($user->getEmail());
            $event2 = new ContactSentEvent($user->getEmail(), $user->getPassword());

            //On dispatch l'event
            $dispatcher->dispatch($event);
            $dispatcher->dispatch($event2);

            return $this->redirectToRoute('app_post');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
