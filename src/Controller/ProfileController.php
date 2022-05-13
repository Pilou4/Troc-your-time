<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Profile;
use App\Form\ProfileType;
use App\Entity\ProfileSearch;
use App\Form\ProfileAdressType;
use App\Form\ProfileSearchType;
use App\Form\PictureProfileType;
use App\Form\ProfileProposeType;
use App\Form\ProfileCivilityType;
use App\Repository\ProfileRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
        )
    {
        
    }

    #[Route('/me', name: 'me')]
    public function me(): Response
    {
        return $this->render('profile/me.html.twig');
    }

    #[Route('/community', name: 'community')]
    public function community(
        ProfileRepository $profileRepository,
        Request $request,
        CategoryRepository $categoryRepository
        ): Response
    {
        $page =  $request->query->getInt('page', 1);
        $search = new ProfileSearch();
        $form = $this->createForm(ProfileSearchType::class, $search);
        $form->handleRequest($request);

        return $this->render('profile/community.html.twig', [
            'profiles' => $profileRepository->findAllVisibleQuery($search),
            'categories' => $categoryRepository->findAll(),
            'page'       => $page,
            'form' => $form->createView()
        ]);
    }

    #[Route('/data', name: 'data')]
    public function data(): Response
    {
        return $this->render('profile/data.html.twig');
    }

    #[Route('/download', name: 'download')]
    public function download(): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial'); 
        $pdfOptions->setIsRemoteEnabled(true);
        
        $dompdf= new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->renderView('profile/download.html.twig');
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        /** @var $user instanceof User */
        $user = $this->getUser();

        $file = 'profil-data-' . $user->getId() . '.pdf';

        $dompdf->stream($file, [
            'Attachement' => true
        ]);

        return new Response();
    }

    #[Route('/view/{username}', name: 'view')]
    public function view($username, Request $request, ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy(['username' => $username]);

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

        /** @var $user instanceof User */
        $user = $this->security->getUser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Vous devez confirmer votre adresse email pour pouvoir compléter votre profil");
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

    #[Route('/avatar/{username}', name: 'avatar')]
    public function avatar($username, Request $request, Profile $profile): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();
        // dd($user->getProfile()->getId());
        
        if ($username !== $user->getProfile()->getUsername()) {
                $this->addFlash('error', "Vous ne pouvez pas modifier un autre profile");
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(PictureProfileType::class, $profile);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($profile);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre image à bien été enregistrer");
            return $this->redirectToRoute('profile_me');
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
            return $this->redirectToRoute('profile_me');
        }
        return $this->render('profile/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/civility/{id}', name: 'update_civility', requirements: ['id' => '\d+'])]
    public function updateCivility(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileCivilityType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($profile);
            $this->entityManager->flush();


            $this->addFlash('success', "Votre profile à bien été modifié");
            return $this->redirectToRoute('profile_me');
        }

        return $this->render('profile/update_civilty.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/update/address/{id}', name: 'update_address', requirements: ['id' => '\d+'])]
    public function updateAdress(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileAdressType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($profile);
            $this->entityManager->flush();


            $this->addFlash('success', "Votre profile à bien été modifié");
            return $this->redirectToRoute('profile_me');
        }

        return $this->render('profile/update_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/propose/{id}', name: 'update_propose', requirements: ['id' => '\d+'])]
    public function updatePropose(Request $request, Profile $profile): Response
    {
        $form = $this->createForm(ProfileProposeType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($profile);
            $this->entityManager->flush();


            $this->addFlash('success', "Votre profile à bien été modifié");
            return $this->redirectToRoute('profile_me');
        }

        return $this->render('profile/update_propose.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
