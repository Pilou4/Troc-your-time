<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/picture', name: 'admin_picture_')]
#[IsGranted("ROLE_ADMIN")]
class AdminPictureController extends AbstractController
{
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Picture $picture, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($picture);
        $entityManager->flush();

        $this->addFlash("success", "L'image a bien été supprimée");

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}