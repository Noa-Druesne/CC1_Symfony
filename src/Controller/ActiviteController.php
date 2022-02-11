<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activite')]
class ActiviteController extends AbstractController
{
    #[Route('/', name: 'activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createFormBuilder($activite)
            ->add('nom')
            ->add('description')
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activite->setAnimateur($this->getUser());
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ANIMATEUR");
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ANIMATEUR");
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/inscriptionEnfants', name: 'activite_inscription_enfant', methods: ['GET','POST'])]
    public function inscription(Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $activite->addUser($user);
        $entityManager->persist($activite);
        $entityManager->flush();
        return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/desincription', name: 'activite_desinscription', methods: ['GET','POST'])]
    public function desinscription(Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $activite->removeUser($user);
        $entityManager->persist($activite);
        $entityManager->flush();
        return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/listes/{id}', name: 'activite_listes', methods: ['GET'])]
    public function listesEnfants(Activite $activite): Response
    {
        return $this->renderForm('activite/listesEnfants.html.twig', [
            'users' => $activite->getUsers(),
        ]);
    }

    #[Route('/listesInscriptions', name: 'activite_listes_inscription', methods: ['GET'])]
    public function listesInscriptions(): Response
    {
        return $this->renderForm('activite/listesInscription.html.twig', [
            'inscriptions' => $this->getUser()->getEnfants(),
        ]);
    }
}
