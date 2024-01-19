<?php

namespace App\Controller;

use App\Service\PurchaseService;
use App\Traits\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    use Pagination;

    public function __construct(
        private PurchaseService $purchaseService,
    )
    {
    }

    #[Route('/home', name: 'app_home', methods:['GET'])]
    public function index(Request $request): Response
    {
        $array = $this->getItens($request, $this->purchaseService);
        $array['user'] = $this->getUser();

        return $this->render('home/index.html.twig', $array);
    }
}