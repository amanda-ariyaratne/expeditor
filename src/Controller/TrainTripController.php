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
     * @Route("/", name="train_trip_index", methods={"GET"})
     */
    public function index(TrainTripRepository $trainTripRepository): Response
    {
        
        
        $train_trips=  $trainTripRepository->getAll();
        return $this->render('train_trip/index.html.twig', [
            'train_trips' => $train_trips
        ]);
    }

    

    /**
     * @Route("/new", name="train_trip_new", methods={"GET","POST"})
     */
    public function new(Request $request, TrainTripRepository $trainTripRepository): Response
    {
        $trainTrip = new TrainTrip();
        $form = $this->createForm(TrainTripType::class, $trainTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainTrip =  $form->getData();
            $trainTripRepository->insert($trainTrip);
            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/new.html.twig', [
            'train_trip' => $trainTrip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="train_trip_show", requirements={"id"="\d+"})
     */
    public function show(TrainTrip $trainTrip): Response
    {
        return $this->render('train_trip/show.html.twig', [
            'train_trip' => $trainTrip,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="train_trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TrainTrip $trainTrip, TrainTripRepository $trainTripRepository): Response
    {
        $form = $this->createForm(TrainTripType::class, $trainTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainTripRepository->update($trainTrip);

            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/edit.html.twig', [
            'train_trip' => $trainTrip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="train_trip_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, TrainTripRepository $trainTripRepository): Response
    {
        $deleted = false;
        if ($this->isCsrfTokenValid('train_trip', $request->request->get('_token'))) {
            
            $deleted = $trainTripRepository->delete($id);
            return new JsonResponse([
                'status' => $deleted
            ]);
        }

        return $this->redirectToRoute('train_trip_index');
    }
}
