<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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
    public function list(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/list.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/view/{name}', name: 'view')]
    public function view($name, CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/view.html.twig', [
            'categories' => $categoryRepository->findOneBy(['name' => $name])
        ]);
    }

}
