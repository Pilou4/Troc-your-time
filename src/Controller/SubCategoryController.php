<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sub/category', name: 'sub_category_')]
class SubCategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager )
    {
        
    }
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('sub_category/list.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $subCategory = new SubCategory();

        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($subCategory);
            $this->entityManager->flush();

            $this->addFlash('success', "la sous-catégorie à bien été enregistrer");
            return $this->redirectToRoute('sub_category_list');
        }

        return $this->render('sub_category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
