<?php

namespace App\Controller;

use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private PurchaseService $purchaseService
    )
    {
    }

    #[Route('/home', name: 'app_home', methods:['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $purchases = $this->purchaseService->getRepository()->findBy([], ['id' => 'DESC'], 10);

        return $this->render('home/index.html.twig', compact('user', 'purchases'));
    }
}