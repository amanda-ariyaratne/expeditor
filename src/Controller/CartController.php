<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Product;

use App\Form\CartType;
use App\Repository\CartRepository;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/add", name="add_to_cart", methods={"GET","POST"})
     */
    public function add($cart , $customer_id , $product_id ): Response
    {  
        $entityManager = $this->getDoctrine()->getRepository(Cart::class)->insert($cart , $customer_id, $product_id);
        
    }


    /**
     * @Route("/", name="cart_index", methods={"GET"})
     */
    public function index(CartRepository $cartRepository , ProductRepository $productRepository, Security $security): Response
    {
        $user = $security->getUser();
        $cart = $cartRepository->getAllByCustomerID($user->getId());
        $cart_products = array();
        if (count($cart) != 0){
            foreach($cart as $c){
                $c['product']= $productRepository->getProductByID($c['product_id']);

                //calculate total
                $product =  $c['product'][0];
                if( $c['quantity'] > $product['retail_limit'] ){//whole sale
                    $c['total'] = $c['quantity']*$product['wholesale_price'] ;
                }
                else{//retail
                    $c['total'] = $c['quantity']*$product['retail_price'] ;
                }
                array_push($cart_products , $c);
            }
        }

        return $this->render('cart/index.html.twig', [
            'carts' => $cart_products,
        ]);
    }

    /**
     * @Route("/new", name="cart_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/new.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_show", methods={"GET"})
     */
    public function show(Cart $cart , ProductRepository $productRepository): Response
    {   
        $cart_product = array();
        $cart_product['cart']= $cart;

        $cart_product['product']= $productRepository->getProductByID($cart->getProduct()->getId());

        $product =  $cart_product['product'][0];
        if( $cart->getQuantity() > $product['retail_limit']  ){//whole sale
            $cart_product['total'] = $cart->getQuantity()*$product['wholesale_price'] ;
        }
        else{//retail
            $cart_product['total'] = $cart->getQuantity()*$product['retail_price'] ;
        }
        // var_dump($cart_product['total']);
        // die();

        return $this->render('cart/show.html.twig', [
            'cart' => $cart_product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cart $cart): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cart $cart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $cart_deleted = $entityManager->getRepository(Cart::class)->deleteById($cart->getId());
        }

        return $this->redirectToRoute('cart_index');
    }


    public function noOfCartItems($id): Response
    {
        $cart = $cartRepository->getAllByCustomerID($id);
        return count($cart);
    }
}
