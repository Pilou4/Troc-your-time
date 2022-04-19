<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Form\ResetPassType;
use App\Form\ResetPasswordType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Form\UserPasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Mailer $mailer,
        private UserRepository $userRepository
        )
    {
        
    }

    #[Route('/create-account', name: 'create_account')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            $this->addFlash("message", "Vous ne pouvez pas créer un compte car vous êtes déjà membres");
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setToken($this->generateToken());
            $message = 'merci de confirmer votre addresse email';
            $template ='registration/confirm-account.html.twig';

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            // do anything else you need here, like send an email

            $this->mailer->sendEmail($user->getEmail(), $user->getToken(),$message, $template);

            $this->addFlash("success", "Un email vous été envoyé. Merci de valider votre compte !");
            return $this->redirectToRoute('profile_add');

            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );
        }


        return $this->render('registration/create-account.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param string $token
     */
    #[Route('/confirm-account/{token}', name: 'confirm_account')]
    public function confirmAccount(string $token, Request $request, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator)
    {
        $user = $this->userRepository->findOneBy(["token" => $token]);
        
        if(!$user) {
            throw $this->createNotFoundException("Ce compte n'exsite pas");
        }
        
        $user->setToken(null);
        $user->setIsVerified(true);
        $user->setRoles(["ROLE_USER_VALIDATE"]);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->addFlash("success", "Email vérifier, merci de compléter votre profil !");
        return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            ); 
        $this->redirectToRoute("profile_add");           
        
    }

    #[Route('/forgotten-password', name: 'forgotten_password')]
    public function forgottenPassword (Request $request, TokenGeneratorInterface $tokenGenerator) {
        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var User $user */
            $user = $this->userRepository->findOneByEmail($data['email']);
            
            if (!$user) {
                $this->addFlash('danger', 'Cette adresse n\'existe pas');
                $this->redirectToRoute('homepage');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setToken($token);
                
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur estsurvenue : ' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // $url = $this->generateUrl('password_reset', ['token' => $token]);
            $message = 'mot de passe oublié';
            $template ='registration/password-reset.html.twig';

            $this->mailer->sendEmail($user->getEmail(), $user->getToken(), $message, $template);
            $this->addFlash("message", "Un e-mail de réinitialisation de mot de passe vous a été envoyé"); 
            
            return $this->redirectToRoute("app_login");
        }
        return $this->render('registration/forgotten_password.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/change-password', name: 'change_password')]
    #[IsGranted('ROLE_USER')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordEncoder) 
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserPasswordUpdateType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('newPassword')->getData();
            $encodedPassword = $passwordEncoder->hashPassword($user, $plainPassword); 
            $user->setPassword($encodedPassword);

            $this->entityManager->flush();
            $this->addFlash("success", "Le mot de passe à bien été modifié");
            return $this->redirectToRoute("homepage");
        }

        return $this->render(
            'registration/change-password.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }
    
    /**
     * @param string $token
     */
    #[Route('/password-reset/{token}', name: 'password_reset')]
    public function resetPassword(string $token, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['token' => $token]);
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if(!$user) {
            $this->addFlash('danger', 'token inconnu');
            return $this->redirectToRoute('app_login');
        }
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $plainPassword = $form->get('password')->getData();
            $encodedPassword = $passwordEncoder->hashPassword($user, $plainPassword); 
            $user->setPassword($encodedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succés');
            $this->redirectToRoute('app_login');
        }         
        return $this->render('registration/reset.html.twig', 
            [
                'form' => $form->createView(),
                'token' => $token
            ]
        );
    }

    #[Route('/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'])]
    public function delete(User $user)
    {

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash("success", "Votre compte a bien été supprimée");

        return $this->redirectToRoute('homepage');
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
