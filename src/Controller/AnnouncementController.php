<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Announcement;
use App\Form\AnnouncementType;
use App\Entity\AnnouncementSearch;
use App\Form\AnnouncementSearchType;
use App\Form\AnnouncementMessageType;
use App\Repository\ProfileRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubCategoryRepository;
use App\Repository\AnnouncementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/announcement', name: 'announcement_')]
class AnnouncementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private MailerInterface $mailerInterface
        )
    {
    }

    // #[Route('/search', name: 'search')]
    // public function search(PaginatorInterface $paginator, Request $request, AnnouncementRepository $announcementRepository): Response
    // {
    //     $search = new AnnouncementSearch();
    //     $form = $this->createForm(AnnouncementSearchType::class, $search);
    //     $form->handleRequest($request);

    //     $announcement = $paginator->paginate(
    //         $announcementRepository->findAllVisibleQuery($search),
    //         $request->query->getInt('page', 1),
    //         2
    //     );

    //     return $this->render('announcement/search.html.twig', [
    //         'announcements' => $announcement,
    //         'form'          => $form->createView()
    //     ]);
    // }
    
    #[Route('/search', name: 'search')]
    public function search(
        PaginatorInterface $paginator,
        Request $request,
        AnnouncementRepository $announcementRepository,
        CategoryRepository $categoryRepository,
        ): Response
    {
        $search = new AnnouncementSearch();

        $page =  $request->query->getInt('page', 1);

        $limit = 2;

        // $announces = $announcementRepository->getPaginate($page, $limit);

        $form = $this->createForm(AnnouncementSearchType::class, $search);
        $form->handleRequest($request);

        $subCategories = $request->get("subCategories");

        $announces = $paginator->paginate(
            $announcementRepository->findAllVisibleQuery($search, $subCategories),
            $request->query->getInt('page', 1),
            3
        );

        if ($request->get("ajax")) {
            return new JsonResponse([
                'content' => $this->renderView('announcement/_content.html.twig', [
                    'announces' => $announces,
                    'form'          => $form->createView(),
                    'page' => $page
                ])
            ]);
        }

        return $this->render('announcement/search.html.twig', [
            'announcesAll' => $announcementRepository->findBy(['isOnline' => 1]),
            'announces'  => $announces,
            'categories' => $categoryRepository->findAll(),
            'form'       => $form->createView(),
            'page'       => $page
        ]);
    }

    #[Route('/view/{id}/{slug}', name: 'view', requirements: ['id' => '\d+'])]
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
            $this->addFlash('message', "Vous devez compl??ter votre profil pour envoyer un message");
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

            $this->addFlash('success', "Votre message ?? bien ??t?? envoyer");
            return $this->redirectToRoute('announcement_list');
        }

        return $this->render('announcement/contact.html.twig', [
            'form' => $form->createView(),
            'title' => $title
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
            throw new NotFoundHttpException('Pas d\'annonce trouv??e');
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
            throw new NotFoundHttpException('Pas d\'annonce trouv??e');
        }
        /** @var $user instanceof User */
        $user = $this->security->getuser();
        $announce->removeFavorite($user->getProfile());

        $this->entityManager->persist($announce);
        $this->entityManager->flush();
        return $this->redirectToRoute('announcement_list');
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/favorite', name: 'favorite')]
    public function favorite(): Response
    {        
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        if ($user->getProfile() == null) {
            $this->addFlash('message', "Pour acc??der au favoris vous devez compl??ter votre profil");
            return $this->redirectToRoute('profile_add');
        }

        return $this->render('announcement/favorite.html.twig');
    }


    #[Route('/online/{id}', name: 'online', requirements: ['id' => '\d+'])]
    public function online(Announcement $announce)
    {
        $announce->setIsOnline(($announce->getIsOnline()) ? false : true);

        $this->entityManager->persist($announce);
        $this->entityManager->flush();

        return new Response("true");
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(Request $request): Response
    {
        $announcement = new Announcement();

        /** @var $user instanceof User */
        $user = $this->security->getuser();
        
        
        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compl??ter votre profil pour enregistrer une annonce");
            return $this->redirectToRoute('profile_add');
        }

        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        /** @var $user instanceof User */
        $user = $this->security->getUser();
        if ($user->isVerified() === false) {
            $this->addFlash('error', "Adresse email non v??rifi??");
            return $this->redirectToRoute('homepage');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($announcement);
            $this->entityManager->flush();

            $email = (new TemplatedEmail())
                ->from($user->getEmail())
                ->to("fred@frederic-caffier.fr")
                ->subject('contact sur troc time')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'firstname' => $user->getProfile()->getFirstname(),
                    'lastname' => $user->getProfile()->getLastname(),
                    'object' => "ATTENTES ANNONCES EN LIGNE",
                    'mail' => $user->getEmail(),
                    'message' => "Une annonce viens d'???tre publi?? sur le site de TROC-SERVICE"
                ]);
            // Envoie du mail
            $this->mailerInterface->send($email);


            $this->addFlash('success', "Votre annonce ?? bien ??t?? enregistrer");
            return $this->redirectToRoute('announcement_list');
        }
        return $this->render('announcement/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function upadte(Request $request, Announcement $announcement): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);
        $form->handleRequest($request);

        /** @var $user instanceof User */
        $user = $this->security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $announcement->setIsOnline(false);
            $this->entityManager->persist($announcement);
            $this->entityManager->flush();

            $email = (new TemplatedEmail())
                ->from($user->getEmail())
                ->to("fred@frederic-caffier.fr")
                ->subject('contact sur troc time')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'firstname' => $user->getProfile()->getFirstname(),
                    'lastname' => $user->getProfile()->getLastname(),
                    'object' => "ANNONCES MODIFIER A V??RIFIER",
                    'mail' => $user->getEmail(),
                    'message' => "Une annonce viens d'???tre publi?? sur le site de TROC-SERVICE"
                ]);

            // Envoie du mail
            $this->mailerInterface->send($email);
            $this->addFlash('success', "L'annonce a bien ??t?? modifier");
            return $this->redirectToRoute('announcement_me');
        }
        return $this->render('announcement/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Announcement $announcement)
    {
         /** @var $user instanceof User */
        $user = $this->security->getUser();

        if ($announcement->getProfile()->getId() === $user->getProfile()->getId()) {
            $this->entityManager->remove($announcement);
            $this->entityManager->flush();
            $this->addFlash("success", "L'annonce a bien ??t?? supprim??e");
            return $this->redirectToRoute('announcement_me');
        }

        $this->addFlash("error", "Vous ne pouvez pas supprimer l'annonce d'un autre utilisateur");
        return $this->redirectToRoute('announcement_me');

        
    }
}
