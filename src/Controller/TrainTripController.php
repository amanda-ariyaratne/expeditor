<?php

namespace App\Controller;

use App\Entity\TrainTrip;
use App\Form\TrainTripType;
use App\Repository\TrainTripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('train_trip/index.html.twig', [
            'train_trips' => $trainTripRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="train_trip_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trainTrip = new TrainTrip();
        $form = $this->createForm(TrainTripType::class, $trainTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trainTrip);
            $entityManager->flush();

            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/new.html.twig', [
            'train_trip' => $trainTrip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="train_trip_show", methods={"GET"})
     */
    public function show(TrainTrip $trainTrip): Response
    {
        return $this->render('train_trip/show.html.twig', [
            'train_trip' => $trainTrip,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="train_trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TrainTrip $trainTrip): Response
    {
        $form = $this->createForm(TrainTripType::class, $trainTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('train_trip_index');
        }

        return $this->render('train_trip/edit.html.twig', [
            'train_trip' => $trainTrip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="train_trip_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TrainTrip $trainTrip): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainTrip->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trainTrip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('train_trip_index');
    }
}
