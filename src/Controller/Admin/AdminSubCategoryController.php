<?php

namespace App\Controller\Admin;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/sub/category', name: 'admin_sub_')]
#[IsGranted("ROLE_ADMIN")]
class AdminSubCategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/list', name: 'category_list')]
    public function list(SubCategoryRepository $subCategoryRepository): Response
    {
        return $this->render('admin/sub_category/list.html.twig', [
            'subCategories' => $subCategoryRepository->findAll()
        ]);
    }

    #[Route('/add', name: 'category_add')]
    public function add(Request $request): Response
    {
        $subCategory = new SubCategory();

        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($subCategory);
            $this->entityManager->flush();

            $this->addFlash('success', "La sous-catégorie à bien été enregistrer");
            return $this->redirectToRoute('admin_sub_category_list');
        }
        return $this->render('admin/sub_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'category_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, SubCategory $subCategory): Response
    {
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($subCategory);
            $this->entityManager->flush();


            $this->addFlash('success', "La sous catégorie à bien été modifié");
            return $this->redirectToRoute('admin_sub_category_list');
        }
        return $this->render('admin/sub_category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'category_delete', requirements: ['id' => '\d+'])]
    public function delete(SubCategory $subCategory)
    {
        $this->entityManager->remove($subCategory);
        $this->entityManager->flush();

        $this->addFlash("success", "La sous catégorie a bien été supprimée");

        return $this->redirectToRoute('admin_sub_category_list');
    }
}
