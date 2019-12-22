<?php

namespace App\Controller;

use App\Entity\TruckRoute;
use App\Entity\StoreManager;
use App\Form\TruckRouteType;
use App\Repository\TruckRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $user = $this->getUser()->getId();
        $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();

        return $this->render('truck_route/index.html.twig', [
            'truck_routes' => $truckRouteRepository->getAll($store),
        ]);
    }

    /**
     * @Route("/new", name="truck_route_new", methods={"GET","POST"})
     */
    public function new(Request $request, TruckRouteRepository $truckRouteRepository): Response
    {
        $user = $this->getUser()->getId();
        $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();

        $truck_route = new TruckRoute();
        $form = $this->createForm(TruckRouteType::class, $truck_route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {     
            $truckRouteRepository->insert($truck_route, $store);
            return $this->redirectToRoute('truck_route_index');
        }

        return $this->render('truck_route/new.html.twig', [
            'truck_route' => $truck_route,
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
    public function edit(Request $request, TruckRoute $truck_route, TruckRouteRepository $truckRouteRepository): Response
    {
        $user = $this->getUser()->getId();
        $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();

        $form = $this->createForm(TruckRouteType::class, $truck_route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser()->getId();
            $truckRouteRepository->update($truck_route, $store);
            return $this->redirectToRoute('truck_route_index');
        }

        return $this->render('truck_route/edit.html.twig', [
            'truck_route' => $truck_route,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="truck_route_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, TruckRouteRepository $truckRouteRepository): Response
    {   
        $deleted = false;
        if ($this->isCsrfTokenValid('truck-route-token', $request->request->get('_token'))) {
            
            $deleted = $truckRouteRepository->delete($id);
            return new JsonResponse([
                'status' => $deleted
            ]);
        }
        return new JsonResponse(['status' => $deleted]);
    }
}
