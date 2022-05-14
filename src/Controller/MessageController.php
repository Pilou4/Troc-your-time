<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/message', name: 'message_'), IsGranted("ROLE_USER")]
class MessageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
        )
    {
        
    }
    
    #[Route('/list', name: 'list')]
    public function list(ProfileRepository $profileRepository): Response
    {        
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour accéder à la messagerie");
            return $this->redirectToRoute('profile_add');
        }

        return $this->render('message/list.html.twig', [
            'sender' => $profileRepository->findSenderMessage($user->getProfile()->getId()),
            'received' => $profileRepository->findReceivedMessage($user->getProfile()->getId())
        ]);
    }

    #[Route('/received', name: 'received')]
    public function received(ProfileRepository $profileRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        return $this->render('message/received.html.twig', [
            'received' => $profileRepository->findReceivedMessage($user->getProfile()->getId())
        ]);
    }

    #[Route('/sent', name: 'sent')]
    public function sent(ProfileRepository $profileRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        return $this->render('message/sent.html.twig', [
            'sender' => $profileRepository->findSenderMessage($user->getProfile()->getId()),
        ]);
    }

    #[Route('/contact/{username}', name: 'contact')]
    public function contact($username, Request $request, ProfileRepository $profileRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour envoyer un message");
            return $this->redirectToRoute('profile_add');
        }
        
        $message = new Message();
        $recpient = $profileRepository->findOneBy(['username' => $username]);


        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($user->getProfile());
            $message->setRecipient($recpient);
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre message à bien été envoyer");
            return $this->redirectToRoute('message_list');
        }

        return $this->render('message/contact.html.twig', [
            'form' => $form->createView(),
            'username' => $username
        ]);
    }

    #[Route('/send', name: 'send')]
    public function send(Request $request): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour envoyer un message");
            return $this->redirectToRoute('profile_add');
        }
        
        $message = new Message();
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($user->getProfile());
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre message à bien été envoyer");
            return $this->redirectToRoute('message_list');
        }

        return $this->render('message/send.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/basket', name: 'basket')]
    public function basket(): Response
    {
        return $this->render('message/basket.html.twig');
    }

    #[Route('/read/{id}', name: 'read')]
    public function read(Message $message): Response
    {
        $message->setIsRead(true);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->render('message/read.html.twig', compact("message"));
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Message $message)
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        // Si l'utilisateur à envoyé le message
        if ($message->getSender()->getId() == $user->getId()) {
            if ($message->getIsRecipientDelete() == true ) {
                $this->entityManager->remove($message);
                $this->entityManager->flush();

                $this->addFlash("success", "Le message a bien été supprimée");
                return $this->redirectToRoute('message_list');
            }
            else {
                $message->setIsSenderDelete(true);
                $this->entityManager->flush();
                $this->addFlash("success", "Le message a bien été supprimée");
                return $this->redirectToRoute('message_list');
            }
        }

        // Si l'utilisateur à reçu le message
        if ($message->getRecipient()->getId() == $user->getId()) {
            if ($message->getIsSenderDelete() == true) {
                $this->entityManager->remove($message);
                $this->entityManager->flush();

                $this->addFlash("success", "Le message a bien été supprimée");
                return $this->redirectToRoute('message_list');
            }
            else {
                $message->setIsRecipientDelete(true);
                $this->entityManager->flush();
                $this->addFlash("success", "Le message a bien été supprimée");
                return $this->redirectToRoute('message_list');
            }
        }
        
        return $this->redirectToRoute('message_list');
    }

}
