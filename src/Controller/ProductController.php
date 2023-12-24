<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\RegisterProductType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    #[Route('/admin/products', name: 'app_admin_products', methods:['GET'])]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $products = $this->productService->getRepository()->findAll();

        return $this->render('product/index.html.twig', compact('user', 'products'));
    }

    #[Route('/admin/products/register', name: 'app_admin_products_register', methods:['GET', 'POST'])]
    public function register(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $products = $this->productService->getRepository()->findAll();

        $product = new Product();
        $registerForm = $this->createForm(RegisterProductType::class, $product);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $this->productService->save($product);
            $this->addFlash('success', 'Produto cadastrado com sucesso');
            return $this->redirectToRoute('app_admin_products', [], 201);
        }

        return $this->render('product/registerProduct.html.twig', compact('user', 'products', 'registerForm'));
    }

    #[Route('/admin/products/remove/{productToRemove}', name: 'app_admin_products_remove', methods:['POST'])]
    public function remove(Request $request, Product $productToRemove): Response
    {
        $success = $this->productService->remove($productToRemove);

        if($success){
            $this->addFlash('success', 'Produto removido com sucesso');
        } else {
            $this->addFlash('danger', 'Erro ao remover o produto');
        }

        return $this->redirectToRoute('app_admin_products');
    }
}
