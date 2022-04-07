<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category', name: 'admin_category_')]
class AdminCategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/list', name: 'list')]
    public function list(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
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
            return $this->redirectToRoute('admin_category_list');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();


            $this->addFlash('success', "La catégorie à bien été modifié");
            return $this->redirectToRoute('admin_category_list');
        }
        return $this->render('admin/category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Category $category)
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->addFlash("success", "La catégorie a bien été supprimée");

        return $this->redirectToRoute('admin_category_list');
    }
}
