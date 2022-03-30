<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\PictureProfileType;

#[Route('/profile', name: 'profile_'), IsGranted("ROLE_USER")]
class ProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        return $this->render('profile/list.html.twig');
    }

    #[Route('/view/{id}', name: 'view')]
    public function view($id, Request $request, ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy(['id' => $id]);

        return $this->render('profile/view.html.twig', [
            'profile' => $profile
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request): Response
    {
        $profile = new Profile();

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        /** @param $user instanceof User */
        $user = $this->getUser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Adresse email non vérifié");
            return $this->redirectToRoute('homepage');
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($profile);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre profil à bien été enregistrer");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('profile/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/avatar/{id}', name: 'avatar')]
    public function avatar(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(PictureProfileType::class, $profile);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($profile);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre image à bien été enregistrer");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('profile/avatar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($profile);
            $this->entityManager->flush();


            $this->addFlash('success', "Votre profile à bien été modifié");
            return $this->redirectToRoute('profile_list');
        }
        return $this->render('profile/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
