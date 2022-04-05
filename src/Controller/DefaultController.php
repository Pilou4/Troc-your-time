<?php

namespace App\Controller;

use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(
        AnnouncementRepository $announcementRepository,
        CategoryRepository $categoryRepository,
        ): Response
    {
        return $this->render('default/homepage.html.twig', [
            'announcements' => $announcementRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
