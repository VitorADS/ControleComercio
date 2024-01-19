<?php

namespace App\Controller;

use App\DTO\PaymentDTO;
use App\DTO\PurchaseItemDTO;
use App\Entity\Payment;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\PurchaseFormType;
use App\Form\PurchaseItemType;
use App\Service\PaymentService;
use App\Service\PurchaseItemService;
use App\Service\PurchaseService;
use App\Traits\Form;
use App\Traits\Pagination;
use App\Utils\FormatNumber;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    use Form, Pagination;

    public function __construct(
        private PurchaseService $purchaseService,
        private PurchaseItemService $purchaseItemService,
        private PaymentService $paymentService
    )
    {
    }

    #[Route('/purchase/index', name: 'app_purchase_index', methods:['GET'])]
    public function purchase(Request $request): Response
    {
        $array = $this->getItens($request, $this->purchaseService);
        $array['user'] = $this->getUser();

        return $this->render('purchase/index.html.twig', $array);
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

    #[Route('/purchase/edit/{purchase}', name: 'app_purchase_edit', methods:['GET'])]
    public function purchaseEdit(Request $request, Purchase $purchase, array $array = []): Response
    {
        $array['purchase'] = $purchase;
        $array['user'] = $this->getUser();
        $purchaseItemDTO = new PurchaseItemDTO();
        $paymentDTO = new PaymentDTO();
        $paymentDTO->total = $purchase->getRemainingValue();
        $paymentDTO->purchase = $purchase;

        $array = array_merge($array, $this->getItens($request, $this->purchaseItemService, ['purchase' => $purchase->getId()]));
        $array['purchaseItemForm'] = $this->createPurchaseForm($purchaseItemDTO, $purchase);
        $array['paymentForm'] = $this->createPaymentForm($paymentDTO);

        $array['toPay'] = FormatNumber::format($purchase->getRemainingValue());
        $array['payments'] = $purchase->getPayments();

        return $this->render('purchase/edit.html.twig', $array);
    }

    #[Route('/purchase/add-payment/{purchase}', name: 'app_purchase_add_payment', methods:['POST'])]
    public function purchaseAddPayment(Request $request, Purchase $purchase): Response
    {
        try{
            $paymentDTO = new PaymentDTO();
            $paymentDTO->userSystem = $this->getUser();
            $paymentDTO->purchase = $purchase;
            $paymentForm = $this->createPaymentForm($paymentDTO)
                ->handleRequest($request);

            if($paymentForm->isSubmitted() && $paymentForm->isValid()){
                $this->paymentService->addPayment($paymentDTO);
            }

            $this->addFlash('success', 'Pagamento adicionado!');
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('app_purchase_edit', ['purchase' => $purchase->getId()]);
    }

    #[Route('/purchase/remove-payment/{payment}', name: 'app_purchase_remove_payment', methods:['POST'])]
    public function purchaseRemovePayment(Request $request, Payment $payment): Response
    {
        try{
            $this->paymentService->remove($payment);
            $this->addFlash('success', 'Pagamento removido!');
        }catch(Exception $e){
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('app_purchase_edit', ['purchase' => $payment->getPurchase()->getId()]);
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
            return $this->redirectToRoute('app_purchase_index');
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
