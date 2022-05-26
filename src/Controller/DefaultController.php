<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\AnnouncementRepository;
use App\Repository\ProfileRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(
        AnnouncementRepository $announcementRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        ProfileRepository $profileRepository,
        Request $request
        ): Response
    {
        // $announcement = $paginator->paginate(
        //     $announcementRepository->findAllOrderedByDate(),
        //     $request->query->getInt('page', 1),
        //     6
        // );

        // $profile = $paginator->paginate(
        //     $profileRepository->findAll(),
        //     $request->query->getInt('page', 1),
        //     6
        // );

        return $this->render('default/homepage.html.twig', [
            'announces' => $announcementRepository->findAllOrderedByDate(),
            'categories' => $categoryRepository->findAllOrderedByName(),
            'profiles' => $profileRepository->findAllOrderedByDate(),
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(
        AnnouncementRepository $announcementRepository,
        ProfileRepository $profileRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
        $announcement = $paginator->paginate(
            $announcementRepository->findAllOrderedByDate(),
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('default/test.html.twig', [
            'announcements' => $announcement,
            'categories' => $categoryRepository->findAllOrderedByName(),
            'profiles' => $profileRepository->findAll()
        ]);
    }
}
