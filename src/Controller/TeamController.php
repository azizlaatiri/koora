<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/clubs', name: 'club_list')]
    public function listClubs(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les clubs depuis la base de données
        $clubs = $entityManager->getRepository(Club::class)->findAll();

        return $this->render('club/list.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    #[Route('/club/add', name: 'add_club')]
    public function addClub(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouveau Club
        $club = new Club();

        // Créer le formulaire pour ajouter un club
        $form = $this->createForm(TeamType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder le nouveau club dans la base de données
            $entityManager->persist($club);
            $entityManager->flush();

            // Rediriger vers la liste des clubs après l'ajout
            return $this->redirectToRoute('club_list');
        }

        return $this->render('club/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

#[Route('/club/{id}/remove', name: 'remove_club')]
public function removeClub(Club $club, EntityManagerInterface $entityManager): Response
{
    // Supprimer le club
    $entityManager->remove($club);
    $entityManager->flush();

    // Rediriger vers la liste des clubs après suppression
    return $this->redirectToRoute('club_list');
}

#[Route('/club/{id}/edit', name: 'edit_club')]
    public function updateClub(Club $club, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire avec les données existantes du club
        $form = $this->createForm(TeamType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le club dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des clubs après la modification
            return $this->redirectToRoute('club_list');
        }

        return $this->render('club/add.html.twig', [
            'form' => $form->createView(),
            'club' => $club,
        ]);
    }

#[Route('/club/{id}/details', name: 'club_detail')]
    public function clubDetails(Club $club): Response
    {
        // Rendre la vue qui affiche les détails du club
        return $this->render('club/details.html.twig', [
            'club' => $club,
        ]);
    }
}