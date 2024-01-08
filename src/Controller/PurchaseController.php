<?php

namespace App\Controller;

use App\DTO\PurchaseItemDTO;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\PurchaseFormType;
use App\Form\PurchaseItemType;
use App\Service\PurchaseItemService;
use App\Service\PurchaseService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    public function __construct(
        private PurchaseService $purchaseService,
        private PurchaseItemService $purchaseItemService
    )
    {
    }

    #[Route('/purchase/index', name: 'app_purchase_index', methods:['GET'])]
    public function purchase(): Response
    {
        $user = $this->getUser();
        $purchases = $this->purchaseService->getRepository()->findAll();

        return $this->render('purchase/index.html.twig', compact('user', 'purchases'));
    }

    #[Route('/purchase/create', name: 'app_purchase_create', methods:['GET', 'POST'])]
    public function purchaseCreate(Request $request): Response
    {
        $user = $this->getUser();
        $purchase = new Purchase();

        $purchaseForm = $this->createForm(PurchaseFormType::class, $purchase, ['edit' => false]);
        $purchaseForm->handleRequest($request);

        if($purchaseForm->isSubmitted() && $purchaseForm->isValid()){
            $purchase->setUserSystem($user);
            $purchase = $this->purchaseService->save($purchase);

            if($purchase instanceof Purchase){
                $this->addFlash('success', 'Venda criada!');
                return $this->redirectToRoute('app_purchase_edit', ['purchase' => $purchase->getId()], 201);
            }
        }

        return $this->render('purchase/create.html.twig', compact('user', 'purchaseForm'));
    }

    #[Route('/purchase/edit/{purchase}', name: 'app_purchase_edit', methods:['GET', 'POST'])]
    public function purchaseEdit(Request $request, Purchase $purchase): Response
    {
        $user = $this->getUser();
        $purchaseItemDTO = new PurchaseItemDTO();
        $purchaseItemDTO->purchase = $purchase->getId();
        $purchaseItemForm = $this->createForm(
            PurchaseItemType::class,
            $purchaseItemDTO,
            [
                'action' => $this->generateUrl('app_purchase_add_item'),
                'method' => 'POST'
            ]
        );

        $toPay = number_format($purchase->getRemainingValue(), 2, ',');
        $purchaseItems = $this->purchaseItemService->findBy(['purchase' => $purchase->getId()]);
        return $this->render('purchase/edit.html.twig', compact(
            'user',
            'purchase',
            'purchaseItemForm',
            'purchaseItems',
            'toPay'
        ));
    }

    #[Route('/purchase/remove/{purchase}', name: 'app_purchase_remove', methods:['POST'])]
    public function purchaseRemove(Request $request, Purchase $purchase): Response
    {
        try{
            $this->purchaseService->remove($purchase);
            $this->addFlash('success', 'Venda removida!');

            return $this->redirectToRoute('app_purchase_index');
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $purchase->getId()]);
        }
    }

    #[Route('/purchase/finish/{purchase}', name: 'app_purchase_finish', methods:['GET'])]
    public function purchaseFinish(Request $request, Purchase $purchase): Response
    {
        try{
            $this->purchaseService->finishPurchase($purchase);
            $this->addFlash('success', 'Venda finalizada!');

            return $this->redirectToRoute('app_purchase_index');
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $purchase->getId()]);
        }
    }

    #[Route('/purchase/addItem', name: 'app_purchase_add_item', methods:['POST'])]
    public function purchaseAddItem(Request $request): Response
    {
        try{
            $input = new PurchaseItemDTO();
            $this->createForm(PurchaseItemType::class, $input)
                ->handleRequest($request);
            $input->userSystem = $this->getUser();
            $this->purchaseItemService->addItem($input);

            $this->addFlash('success', 'Item adicionado!');
            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $input->purchase]);
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $input->purchase]);
        }
    }

    #[Route('/purchase/removeItem/{item}', name: 'app_purchase_remove_item', methods:['POST'])]
    public function purchaseRemoveItem(Request $request, PurchaseItem $item): Response
    {
        try{
            $this->purchaseItemService->removeItem($item);
            $this->addFlash('success', 'Item removido!');

            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $item->getPurchase()->getId()]);
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('app_purchase_edit', ['purchase' => $item->getPurchase()->getId()]);
        }
    }
}
