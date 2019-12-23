<?php

namespace App\Controller;

use App\Entity\TruckRoute;
use App\Form\TruckRouteType;
use App\Repository\TruckRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/truck/route")
 */
class TruckRouteController extends AbstractController
{
    /**
     * @Route("/", name="truck_route_index", methods={"GET"})
     */
    public function index(TruckRouteRepository $truckRouteRepository): Response
    {
        return $this->render('truck_route/index.html.twig', [
            'truck_routes' => $truckRouteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="truck_route_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $truckRoute = new TruckRoute();
        $form = $this->createForm(TruckRouteType::class, $truckRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($truckRoute);
            $entityManager->flush();

            return $this->redirectToRoute('truck_route_index');
        }

        return $this->render('truck_route/new.html.twig', [
            'truck_route' => $truckRoute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="truck_route_show", methods={"GET"})
     */
    public function show(TruckRoute $truckRoute): Response
    {
        return $this->render('truck_route/show.html.twig', [
            'truck_route' => $truckRoute,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="truck_route_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TruckRoute $truckRoute): Response
    {
        $form = $this->createForm(TruckRouteType::class, $truckRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('truck_route_index');
        }

        return $this->render('truck_route/edit.html.twig', [
            'truck_route' => $truckRoute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="truck_route_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TruckRoute $truckRoute): Response
    {
        if ($this->isCsrfTokenValid('delete'.$truckRoute->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($truckRoute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('truck_route_index');
    }
}
