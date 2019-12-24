<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Cart;
use App\Entity\Customer;

use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CartRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Security;

use App\Form\CartType;
/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }


    /**
     * @Route("/productList" , name="productList")
     */
    public function productList(ProductRepository $productRepository): Response 
    {   
        $p = $productRepository->getAllProducts();
        return $this->render('product/productList.html.twig', [
            'products' => $productRepository->getAllProducts(),
        ]);
    }

    /**
     * @Route("/{id}")
     */
    public function product($id ,Request $request, ProductRepository $productRepository, CartRepository $cartRepository , Security $security): Response   
    {
        $product =  $productRepository->getProductByID($id);

        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->getUser() == null){
                return $this->redirectToRoute('back_login');
            }
            $cart  = $form->getData();
            $user = $security->getUser();
            $entitym = $cartRepository->insert($cart , $user->getId() , $product[0]['id']);
        }
        
        return $this->render('product/product.html.twig' ,   ['product' => $product , 'form' => $form->createView(),]);
    }

    /**
     * @Route("/sale/{id}")
     */
    public function sale_product($id ,Request $request, ProductRepository $productRepository, CartRepository $cartRepository , Security $security): Response 
    {
        $product =  $productRepository->getProductByID($id);
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart  = $form->getData();
            var_dump($cart);
            die();
            $user = $security->getUser();
            $entitym = $cartRepository->insert($cart , $user->getId() , $product[0]['id']);
        }
        return $this->render('product/sale_product.html.twig' ,   ['product' => $product , 'form' => $form->createView(),]);
    }

    
    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
