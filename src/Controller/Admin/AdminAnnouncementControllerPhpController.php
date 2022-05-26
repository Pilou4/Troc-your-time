<?php

namespace App\Controller\Admin;

use App\Repository\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/announcement', name: 'admin_announcement_')]
#[IsGranted("ROLE_ADMIN")]
class AdminAnnouncementControllerPhpController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('admin/announcement/list.html.twig', [
            'announces' => $announcementRepository->findAll()
        ]);
    }

    #[Route('/online', name: 'online')]
    public function online(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('admin/announcement/online.html.twig', [
            'announces' => $announcementRepository->findBy(['isOnline' => 1])
        ]);
    }

    #[Route('/wait', name: 'wait')]
    public function wait(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('admin/announcement/wait.html.twig', [
            'announces' => $announcementRepository->findBy(['isOnline' => 0])
        ]);
    }
}
