<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Repository\AnnouncementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

#[Route('/announcement', name: 'announcement_')]
class AnnouncementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
        )
    {
    }
    
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('announcement/list.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);
    }

    #[Route('/view/{id}', name: 'view', requirements: ['id' => '\d+'])]
    public function view($id, AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('announcement/view.html.twig', [
            'announcement' => $announcementRepository->find($id),
        ]);
    }

    #[Route('/me', name: 'me')]
    #[IsGranted("ROLE_USER")]
    public function me(): Response
    {
        return $this->render('announcement/me.html.twig');
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(Request $request): Response
    {
        $announcement = new Announcement();

        /** @var $user instanceof User */
        $user = $this->security->getuser();
        
        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour enregistrer une annonce");
            return $this->redirectToRoute('profile_add');
        }

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        /** @var $user instanceof User */
        $user = $this->getUser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Adresse email non vérifié");
            return $this->redirectToRoute('homepage');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($announcement);
            // dd($announcement);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre annonce à bien été enregistrer");
            return $this->redirectToRoute('announcement_list');
        }
        return $this->render('announcement/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/upadte/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function upadte(Request $request, Announcement $announcement): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($announcement);
            $this->entityManager->flush();

            $this->addFlash('success', "L'annonce a bien été modifier");
            return $this->redirectToRoute('announcement_me');
        }
        return $this->render('announcement/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Announcement $announcement)
    {
        $this->entityManager->remove($announcement);
        $this->entityManager->flush();

        $this->addFlash("success", "L'annonce a bien été supprimée");

        return $this->redirectToRoute('announcement_me');
    }
}
