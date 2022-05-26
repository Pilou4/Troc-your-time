<?php

namespace App\Controller\Admin;

use App\Repository\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'announcesOnline' => $announcementRepository->findBy(['isOnline' => 1]),
            'announcesWait' => $announcementRepository->findBy(['isOnline' => 0])
        ]);
    }
}
