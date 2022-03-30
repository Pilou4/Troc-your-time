<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }
    
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('category/list.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre annonce à bien été enregistrer");
            return $this->redirectToRoute('category_list');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
