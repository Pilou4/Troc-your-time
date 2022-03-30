<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/announcement', name: 'announcement_')]
class AnnouncementController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('announcement/list.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(Request $request): Response
    {
        $announcement = new Announcement();

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        /** @param $user instanceof User */
        $user = $this->getUser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Adresse email non vérifié");
            return $this->redirectToRoute('homepage');
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($announcement);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre annonce à bien été enregistrer");
            return $this->redirectToRoute('announcement_list');
        }
        return $this->render('announcement/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
