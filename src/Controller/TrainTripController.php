<?php

namespace App\Controller;

use App\Entity\TrainTrip;
use App\Form\TrainTripType;
use App\Repository\TrainTripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/train/trip")
 */
class TrainTripController extends AbstractController
{
    
/**
     * @Route("/new", name="train_trip_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $truckRoute = new TrainTrip();
        $form = $this->createForm(TrainTripType::class, $truckRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($truckRoute);
            $entityManager->flush();

            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/new.html.twig', [
            'truck_route' => $truckRoute,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/", name="train_trip_index", methods={"GET"})
     */
    public function index(TrainTripRepository $truckRouteRepository): Response
    {
        return $this->render('train_trip/index.html.twig', [
            'train_trip' => $truckRouteRepository->getAll(),
        ]);
    }
    

    /**
     * @Route("/{id}/edit", name="train_trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TrainTrip $truckRoute): Response
    {
        $form = $this->createForm(TrainTripType::class, $truckRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/edit.html.twig', [
            'train_trip' => $truckRoute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="train_trip_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TrainTrip $truckRoute): Response
    {
        if ($this->isCsrfTokenValid('train_trip', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($truckRoute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('train_trip_index');
    }
}


