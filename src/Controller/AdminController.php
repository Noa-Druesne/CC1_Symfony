<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
* IsGranted("ROLE_ADMIN")
*/
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/ajouterAnimateur/{id}', name: 'ajouterAnimateur', methods: ['GET'])]
    public function ajouterAnimateur(User $user, EntityManagerInterface $entityManager): Response
    {
        $element = 'ROLE_ANIMATEUR';
        $array[] = $element;
        $user->setRoles($array);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
   }

    #[Route('/enleverAnimateur/{id}', name: 'enleverAnimateur', methods: ['GET'])]
    public function enleverAnimateur(User $user, EntityManagerInterface $entityManager): Response
    {
        $array = $this->getUser()->getRoles();
        $element = 'ROLE_ANIMATEUR';
        unset($array[array_search($element, $array)]);
        $user->setRoles($array);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ajouterAdmin/{id}', name: 'ajouterAdmin', methods: ['GET'])]
    public function ajouterAdmin(User $user, EntityManagerInterface $entityManager): Response
    {
        $element = 'ROLE_ADMIN';
        $array[] = $element;
        $user->setRoles($array);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/enleverAdmin/{id}', name: 'enleverAdmin', methods: ['GET'])]
    public function enleverAdmin(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $array = $this->getUser()->getRoles();
        $element = 'ROLE_ADMIN';
        unset($array[array_search($element, $array)]);
        $user->setRoles($array);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }
}
