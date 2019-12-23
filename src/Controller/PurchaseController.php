<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\PurchaseStatus;
use App\Entity\Customer;
use App\Entity\Address;
use App\Entity\Store;
use App\Entity\TruckRoute;
use App\Entity\Cart;

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
        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchaseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="purchase_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        //address info
        $user = $security->getUser();
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
            //add purchase
            $purchase = $form->getData();
            $purchase->setStatus($this->getDoctrine()->getRepository(PurchaseStatus::class)->getByID('1'));
            $purchase->setCustomer($this->getDoctrine()->getRepository(Customer::class)->getByID($user->getId()));
            $purchase->setStore($purchase->getTruckRoute()->getStore());
            $purchase->setAddress($this->getDoctrine()->getRepository(Address::class)->getAddress_afterINSERT($purchase->getAddress()));

            $entitym = $this->getDoctrine()->getRepository(Purchase::class)->insert($purchase);

            //remove from cart
            $entityMDel = $this->getDoctrine()->getRepository(Cart::class)->deleteAllByCustomerId($purchase->getCustomer()->getUser()->getId());

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
