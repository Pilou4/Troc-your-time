<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailerInterface): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $contact = $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to("fred@frederic-caffier.fr")
                ->subject('contact sur troc time')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'firstname' => $contact->get('firstname')->getData(),
                    'lastname' => $contact->get('lastname')->getData(),
                    'object' => $contact->get('object')->getData(),
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData()
                ]);
            // Envoie du mail
            $mailerInterface->send($email);

            // Confirmation d'envoi + message de confirmation
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('contact/index.html.twig', [
            'form' => $contactForm->createView()
        ]);
    }
}
