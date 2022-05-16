<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
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
    public function list(MessageRepository $messageRepository): Response
    {        
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        $recipientMessages = $messageRepository->findBy([
            'recipient' => $user->getProfile(),
            'is_recipient_delete' => 0
        ]);

        $senderMessages = $messageRepository->findBy([
            'sender' => $user->getProfile(),
            'is_sender_delete' => 0
        ]);
        
        if (!$user->getProfile()) {
            $this->addFlash('message', "Vous devez compléter votre profil pour accéder à la messagerie");
            return $this->redirectToRoute('profile_add');
        }

        return $this->render('message/list.html.twig', [
            'sender' => $senderMessages,
            'received' => $recipientMessages
        ]);
    }

    #[Route('/received', name: 'received')]
    public function received(MessageRepository $messageRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();
        $messages = $messageRepository->findBy([
            'recipient' => $user->getProfile(),
            'is_recipient_delete' => 0
        ]);

        // dd($message);
        // dd($profileRepository->findReceivedMessage($user->getProfile()->getId()));

        return $this->render('message/received.html.twig', [
            'messages' => $messages
        ]);
    }

    #[Route('/sent', name: 'sent')]
    public function sent(MessageRepository $messageRepository): Response
    {
        /** @var $user instanceof User */
        $user = $this->security->getuser();

        $messages = $messageRepository->findBy([
            'sender' => $user->getProfile(),
            'is_sender_delete' => 0
        ]);

        return $this->render('message/sent.html.twig', [
            'messages' => $messages
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
