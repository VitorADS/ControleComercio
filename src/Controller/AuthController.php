<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private UserService $userService
    )
    {
    }

    #[Route('/', name: 'app_auth_login', methods:['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'app_auth_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $registerForm = $this->createForm(UserRegisterType::class, $user);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $user->setPassword($hasher->hashPassword($user, $registerForm->get('password')->getData()));
            
            $user = $this->userService->save($user);

            $this->addFlash('success', 'Usuario registrado com sucesso!');
            return $this->redirectToRoute('app_auth_login', [], 201);
        }

        return $this->render('auth/register.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }
}
