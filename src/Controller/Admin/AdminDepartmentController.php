<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/department', name: 'admin_department_')]
class AdminDepartmentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }
    
    #[Route('/list', name: 'list')]
    public function list(DepartmentRepository $departmentRepository): Response
    {
        return $this->render('admin/department/list.html.twig',[
            "departments" => $departmentRepository->findAllOrderedByNumber(),
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $department = new Department();

        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($department);
            $this->entityManager->flush();

            $this->addFlash('success', "Le département à bien été enregistrer");
            return $this->redirectToRoute('admin_department_list');
        }
        return $this->render('admin/department/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Department $department): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($department);
            $this->entityManager->flush();


            $this->addFlash('success', "Le département à bien été modifié");
            return $this->redirectToRoute('admin_department_list');
        }
        return $this->render('admin/department/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Department $department)
    {
        $this->entityManager->remove($department);
        $this->entityManager->flush();

        $this->addFlash("success", "Le département a bien été supprimée");

        return $this->redirectToRoute('admin_department_list');
    }
}
