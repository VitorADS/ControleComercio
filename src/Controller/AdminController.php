<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private UserService $userService
    )
    {   
    }

    #[Route('/admin/users', name: 'app_admin_users', methods:['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $users = $this->userService->getRepository()->findAll();

        return $this->render('admin/index.html.twig', compact('user', 'users'));
    }

    #[Route('/admin/users/remove/{userToRemove}', name: 'app_admin_users_remove', methods:['POST'])]
    public function removeUser(User $userToRemove): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        if($userToRemove->getId() === $user->getId()){
            $this->addFlash('danger', 'Nao e possivel remover o proprio usuario');
        } else {
            $success = $this->userService->remove($userToRemove);

            if($success){
                $this->addFlash('success', 'Usuario removido com sucesso');
            } else {
                $this->addFlash('danger', 'Nao foi possivel remover o usuario');
            }
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/users/register', name: 'app_admin_users_register', methods: ['GET', 'POST'])]
    public function registerUser(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $newUser = new User();
        $registerForm = $this->createForm(UserRegisterType::class, $newUser);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $newUser->setPassword($hasher->hashPassword($newUser, $registerForm->get('password')->getData()));
            
            $newUser = $this->userService->save($newUser);

            $this->addFlash('success', 'Usuario registrado com sucesso!');
            return $this->redirectToRoute('app_admin_users', [], 201);
        }

        $user = $this->getUser();
        return $this->render('admin/registerUser.html.twig', compact('registerForm', 'user'));
    }
}
