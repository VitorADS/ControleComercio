<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\Status;
use App\Service\PurchaseService;
use App\Service\StatusService;
use App\Traits\Pagination;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KitchenController extends AbstractController
{
    use Pagination;

    #[Route('/kitchen', name: 'app_kitchen_home', methods: ['GET'])]
    public function index(Request $request, PurchaseService $purchaseService): Response
    {
        $array = $this->getItens($request, $purchaseService);

        return $this->render('kitchen/index.html.twig', $array);
    }

    #[Route('/kitchen/purchase/{purchase}', name: 'app_kitchen_purchase', methods: ['GET', 'POST'])]
    public function purchase(Request $request, PurchaseService $purchaseService, Purchase $purchase): Response
    {
        if($request->getMethod() === Request::METHOD_POST){
            try{
                /** @var Status $status */
                $status = (new StatusService($purchaseService->getEntityManager()))->findOneBy(['ready' => true]);
                $purchaseService->addStatus($purchase, $status);

                $this->addFlash('success', "Pedido '#{$purchase->getId()} - {$purchase->getBuyerName()}' alterado para Pronto!");
            }catch(Exception $e){
                $this->addFlash('danger', 'Pedido ja possui status de pronto!');
                return $this->redirectToRoute('app_kitchen_purchase', ['purchase' => $purchase->getId()]);
            }

            return $this->redirectToRoute('app_kitchen_home');
        }

        return $this->render('kitchen/purchase.html.twig', compact('purchase'));
    }
}
