<?php

namespace App\Controller;

use App\Entity\TruckTrip;
use App\Entity\Purchase;
use App\Form\TruckTripType;
use App\Form\TruckTrip2Type;
use App\Form\TruckTrip1Type;
use App\Repository\TruckTripRepository;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/truck-trip")
 */
class TruckTripController extends AbstractController
{
    /**
     * @Route("/", name="truck_trip_index", methods={"GET"})
     */
    public function index(TruckTripRepository $truckTripRepository): Response
    {
        $truckTrips = $this->getDoctrine() 
                        ->getRepository(TruckTrip::class)
                        ->getAll();
        return $this->render('truck_trip/index.html.twig', [
            'truck_trip' => $truckTrips
        ]);
    }
    /**
     * @Route("/new", name="truck_trip_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $truckt = new TruckTrip();

        $form = $this->createForm(TruckTripType::class, $truckt);

        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getRepository(TruckTrip::class)->insert($truckt);

            return $this->redirectToRoute('truck_trip_index');
        }

        return $this->render('truck_trip/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/new2", name="truck_trip_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $data=new TruckTrip();
        $form = $this->createForm(TruckTrip1Type::class,$data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            
            //$form = $this->createForm(TruckTrip2Type::class, $data);
            
           
            $entityManager = $this->getDoctrine()->getRepository(TruckTrip::class)->insert($data);
            
            return $this->redirectToRoute('truck_trip_new3',['data'=>$entityManager]);
            /*
            $form = $this->createForm(TruckTrip2Type::class,$truckt);
            
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getRepository(TruckTrip::class)->insert($truckt);
                // data is an array with "name", "email", and "message" keys
                //$data = $form->getData();
                //$form = $this->createForm(TruckTrip2Type::class, $data);
               
                //return $this->redirectToRoute('truck_trip_index');
                return $this->redirectToRoute('truck_trip_index');
            }
         */   
    }
    return $this->render('truck_trip/new2.html.twig', [
        'form' => $form->createView(),
        ]);
    }
    
    
    /**
     * @Route("/new3/{data}", name="truck_trip_new3", methods={"GET","POST"})
     */
    public function new3(Request $request,$data): Response
    {
        //$truckt=new TruckTrip();
        $truckt=$this->getDoctrine() 
        ->getRepository(TruckTrip::class)
        -> getById($data);

        
        
            $form = $this->createForm(TruckTrip2Type::class,$truckt) ; 
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            //$data = $form->getData();
            //$form = $this->createForm(TruckTrip2Type::class, $data);
            $truckt = $form->getData();

            $entityManager = $this->getDoctrine()->getRepository(TruckTrip::class)->update($truckt);

            return $this->redirectToRoute('truck_trip_index');
        

            
        }

        return $this->render('truck_trip/new2.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/assign-products" , name="assigned_purchase_for_truck", methods={"GET","POST"})
     */
    public function purchaseList(PurchaseRepository $purchaseRepository, Request $request,$id): Response 
    {
        /*
        $this->denyAccessUnlessGranted(['ROLE_STORE_MANAGER']);
        
        if($this->isGranted('ROLE_STORE_MANAGER')){
            */
            if ($request->isMethod('post')) {
                $data=($request->request->get('purchase_id'));
                $entityManager = $this->getDoctrine()->getRepository(Purchase::class)->updatePurchase($data,$id);  
                return $this->redirectToRoute('truck_trip_index');
            }
            
        //$request = Request::createFromGlobals();
        

        $doctrine = $this->getDoctrine();
        $purchases = $doctrine->getRepository(Purchase::class)->getProductstoTruck($id);
        

        return $this->render('truck_trip/purchase_assign.html.twig', [
            'purchases' => $purchases
        ]);
    }



    /**
     * @Route("/{id}", name="truck_trip_show", methods={"GET"})
     */
    public function show(TruckTrip $truckTrip): Response
    {
        return $this->render('truck_trip/show.html.twig', [
            'truck_trip' => $truckTrip,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="truck_trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id): Response
    {
        $truckt = $this->getDoctrine() 
                        ->getRepository(TruckTrip::class)
                        ->getById($id);

        $form = $this->createForm(TruckTrip2Type::class, $truckt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $truckt = $form->getData();

            $entityManager = $this->getDoctrine()->getRepository(TruckTrip::class)->update($truckt);

            return $this->redirectToRoute('truck_trip_index');

        }

        return $this->render('truck_trip/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/{id}", name="truck_trip_delete", methods={"DELETE"})
     */
    public function delete(Request $request,  $id,TruckTrip $truckTrip): Response
    {
        if ($this->isCsrfTokenValid('truck-trip', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $truck = $entityManager->getRepository(TruckTrip::class)->delete($id);
            return new JsonResponse([
                'status' => 'true'
            ]);
        }
        
        return new JsonResponse([
            'status' => 'false'
        ]);
    }
}
