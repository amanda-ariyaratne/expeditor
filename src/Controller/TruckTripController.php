<?php

namespace App\Controller;

use App\Entity\TruckTrip;
use App\Entity\Truck;
use App\Form\TruckTripType;
use App\Repository\TruckTripRepository;
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

        $form = $this->createForm(TruckTripType::class, $truckt);
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
