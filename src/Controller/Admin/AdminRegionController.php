<?php

namespace App\Controller\Admin;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/region', name: 'admin_region_')]
class AdminRegionController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/list', name: 'list')]
    public function list(RegionRepository $regionRepository): Response
    {
        return $this->render('admin/region/list.html.twig', [
            'regions' => $regionRepository->findAll()
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $region = new Region();

        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($region);
            $this->entityManager->flush();

            $this->addFlash('success', "La région à bien été enregistrer");
            return $this->redirectToRoute('admin_region_list');
        }
        return $this->render('admin/region/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Region $region): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($region);
            $this->entityManager->flush();


            $this->addFlash('success', "La région à bien été modifié");
            return $this->redirectToRoute('admin_region_list');
        }
        return $this->render('admin/region/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Region $region)
    {
        $this->entityManager->remove($region);
        $this->entityManager->flush();

        $this->addFlash("success", "La region a bien été supprimée");

        return $this->redirectToRoute('admin_region_list');
    }
}
