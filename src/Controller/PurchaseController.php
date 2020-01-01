<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\PurchaseStatus;
use App\Entity\Customer;
use App\Entity\Address;
use App\Entity\Store;
use App\Entity\TruckRoute;
use App\Entity\Cart;
use App\Entity\PurchaseProduct;
use App\Entity\TrainTrip;

use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/purchase")
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/", name="purchase_index", methods={"GET"})
     */
    public function index(PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getUser();
        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchaseRepository->getAllByCustomerID($user->getId()),
        ]);
    }
    /**
     * @Route("/notassigned" , name="not_assigned_productList")
     */
    public function productList(PurchaseRepository $purchaseRepository): Response 
    {/*
        $this->denyAccessUnlessGranted(['ROLE_CHAIN_MANAGER']);

        

        
        if($this->isGranted('ROLE_CHAIN_MANAGER')){
            */
        $doctrine = $this->getDoctrine();
        $purchases = $doctrine->getRepository(Purchase::class)->getNAProducts();
        

        return $this->render('purchase_assign/show_purchase.html.twig', [
            'purchases' => $purchases
        ]);
    }
/**
     * @Route("/notassigned-for-truck" , name="not_assigned_productList")
     */
    public function productListForTruck(PurchaseRepository $purchaseRepository): Response 
    {/*
        $this->denyAccessUnlessGranted(['ROLE_STORE_MANAGER']);

        if($this->isGranted('ROLE_STORE_MANAGER')){
            */
        $doctrine = $this->getDoctrine();
        $purchases = $doctrine->getRepository(Purchase::class)->getNATProducts();
        

        return $this->render('purchase_assign/show_purchase_truck.html.twig', [
            'purchases' => $purchases
        ]);
    }

    /**
     * @Route("/purchase/{id}")
     */
    public function viewOrder($id): Response
    {
        $purchase = $this->getDoctrine()->getRepository(Purchase::class)->getDetailsByPurchaseID($id);
        $total = 0;
        foreach($purchase as $p){
            if($p["quantity"] > $p["retail_limit"]){//wholesale
                $total += $p["quantity"]*$p["wholesale_price"];
            }
            else{//retail
                $total += $p["quantity"]*$p["retail_price"];
            }
        }
        return $this->render('purchase/purchase.html.twig', [
            'purchase' => $purchase,
            'total'=> $total,
        ]);
    }

    /**
     * @Route("/new", name="purchase_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //address info
        $user = $this->getUser();
        $customer = $this->getDoctrine()->getRepository(Customer::class)->getCustomerByID($user->getId());
        $address = $this->getDoctrine()->getRepository(Address::class)->getByAddressID($customer[0]["address_id"]);

        //stores and truck route info
        $stores = $this->getDoctrine()->getRepository(Store::class)->getAll();
        $store_with_routes = array();
        foreach($stores as $store){
            $truckRoute = $this->getDoctrine()->getRepository(TruckRoute::class)->getAll($store->getId());
            
            if(!empty( $truckRoute )){
                $arr = array();
                array_push($arr , $store);
                array_push($arr , $truckRoute);
                array_push($store_with_routes , $arr);
            }
        }
        //form
        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //add to purchase
            $purchase = $form->getData();
            $purchase->setStatus($this->getDoctrine()->getRepository(PurchaseStatus::class)->getByID('1'));
            $purchase->setCustomer($this->getDoctrine()->getRepository(Customer::class)->getByID($user->getId()));
            $purchase->setStore($purchase->getTruckRoute()->getStore());
            $purchase->setAddress($this->getDoctrine()->getRepository(Address::class)->getAddress_afterINSERT($purchase->getAddress()));

            $lastInsertId = $this->getDoctrine()->getRepository(Purchase::class)->insert($purchase);

            //add to purchase_product
            $cart_products = $this->getDoctrine()->getRepository(Cart::class)->getAllByCustomerID($purchase->getCustomer()->getUser()->getId());
            $entityPP = $this->getDoctrine()->getRepository(PurchaseProduct::class)->insert($cart_products , $lastInsertId);

            //remove from cart
            $entityMDel = $this->getDoctrine()->getRepository(Cart::class)->deleteAllByCustomerId($purchase->getCustomer()->getUser()->getId());

            //add to a train trip
            $entityTT = $this->getDoctrine()->getRepository(TrainTrip::class)->assignToTrainTrip($lastInsertId);

            return $this->redirectToRoute('purchase_index');
        }
        return $this->render('purchase/new.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView(),
            'address' => $address,
            'stores'=>$store_with_routes,
        ]);
    }

    /**
     * @Route("/{id}", name="purchase_show", methods={"GET"})
     */
    public function show(Purchase $purchase): Response
    {
        return $this->render('purchase/show.html.twig', [
            'purchase' => $purchase,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="purchase_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Purchase $purchase): Response
    {
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('purchase_index');
        }

        return $this->render('purchase/edit.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="purchase_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Purchase $purchase): Response
    {
        if ($this->isCsrfTokenValid('delete'.$purchase->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($purchase);
            $entityManager->flush();
        }

        return $this->redirectToRoute('purchase_index');
    }
}
