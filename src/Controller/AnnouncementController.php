<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Entity\AnnouncementSearch;
use App\Form\AnnouncementSearchType;
use App\Form\AnnouncementMessageType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnnouncementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/announcement', name: 'announcement_')]
class AnnouncementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
        )
    {
    }

    #[Route('/search', name: 'search')]
    public function search(PaginatorInterface $paginator, Request $request, AnnouncementRepository $announcementRepository): Response
    {
        $search = new AnnouncementSearch();
        $form = $this->createForm(AnnouncementSearchType::class, $search);
        $form->handleRequest($request);

        $announcement = $paginator->paginate(
            $announcementRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('announcement/search.html.twig', [
            'announcements' => $announcement,
            'form'          => $form->createView()
        ]);
    }
    
    #[Route('/list', name: 'list')]
    public function list(PaginatorInterface $paginator, Request $request, AnnouncementRepository $announcementRepository): Response
    {
        $search = new AnnouncementSearch();
        $form = $this->createForm(AnnouncementSearchType::class, $search);
        $form->handleRequest($request);

        $announcement = $paginator->paginate(
            $announcementRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('announcement/list.html.twig', [
            'announcements' => $announcement,
            'form'          => $form->createView()
        ]);
    }

    #[Route('/view/{id}', name: 'view', requirements: ['id' => '\d+'])]
    public function view($id, AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('announcement/view.html.twig', [
            'announcement' => $announcementRepository->find($id),
        ]);
    }

    #[Route('/contact/{username}', name: 'contact')]
    public function contact($username, Request $request, ProfileRepository $profileRepository, AnnouncementRepository $announcementRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour envoyer un message");
            return $this->redirectToRoute('profile_add');
        }
        
        $message = new Message();
        $recpient = $profileRepository->findOneBy(['username' => $username]);
        foreach ($recpient->getAnnouncements() as $announcement) {
            $announce = $announcementRepository->findOneBy(['title' => $announcement->getTitle()]);
            $title = $announce->getTitle();
        }
        
        $form = $this->createForm(AnnouncementMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user->getProfile());
            $message->setTitle("contact au sujet de l'annonce : " . $title);
            $message->setRecipient($recpient);
            $message->setAnnouncement($announce);
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre message à bien été envoyer");
            return $this->redirectToRoute('announcement_list');
        }

        return $this->render('announcement/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/me', name: 'me')]
    #[IsGranted("ROLE_USER")]
    public function me(): Response
    {
        return $this->render('announcement/me.html.twig');
    }

    #[Route('/favorite/add/{id}', name: 'add_favorite')]
    public function ajoutFavoris(Announcement $announce)
    {
        if(!$announce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }
        /** @var $user instanceof User */
        $user = $this->security->getuser();
        $announce->addFavorite($user->getProfile());

        $this->entityManager->persist($announce);
        $this->entityManager->flush();
        return $this->redirectToRoute('announcement_list');
    }

    #[Route('/favorite/remove/{id}', name: 'remove_favorite')]
    public function retraitFavoris(Announcement $announce)
    {
        if(!$announce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }
        /** @var $user instanceof User */
        $user = $this->security->getuser();
        $announce->removeFavorite($user->getProfile());

        $this->entityManager->persist($announce);
        $this->entityManager->flush();
        return $this->redirectToRoute('announcement_list');
    }

    #[Route('/favorite', name: 'favorite')]
    #[IsGranted("ROLE_USER")]
    public function favorite(): Response
    {        
        return $this->render('announcement/favorite.html.twig');
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(Request $request): Response
    {
        $announcement = new Announcement();

        /** @var $user instanceof User */
        $user = $this->security->getuser();
        
        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour enregistrer une annonce");
            return $this->redirectToRoute('profile_add');
        }

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        /** @var $user instanceof User */
        $user = $this->security->getuser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Adresse email non vérifié");
            return $this->redirectToRoute('homepage');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($announcement);
            // dd($announcement);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre annonce à bien été enregistrer");
            return $this->redirectToRoute('announcement_list');
        }
        return $this->render('announcement/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/upadte/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function upadte(Request $request, Announcement $announcement): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($announcement);
            $this->entityManager->flush();

            $this->addFlash('success', "L'annonce a bien été modifier");
            return $this->redirectToRoute('announcement_me');
        }
        return $this->render('announcement/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Announcement $announcement)
    {
        $this->entityManager->remove($announcement);
        $this->entityManager->flush();

        $this->addFlash("success", "L'annonce a bien été supprimée");

        return $this->redirectToRoute('announcement_me');
    }
}
