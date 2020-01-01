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
 * @Route("/truck-route")
 */
class TruckRouteController extends AbstractController
{  
     /**
     * @Route("/", name="truck_route_index", methods={"GET"})
     */
    public function index(TruckRouteRepository $truckRouteRepository): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_STORE_MANAGER', 'ROLE_CHAIN_MANAGER']);

        $doctrine = $this->getDoctrine();

        if ($this->isGranted('ROLE_STORE_MANAGER')){
            $user = $this->getUser()->getId();
            $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();
            $truckRoutes = $truckRouteRepository->getAllByStore($store);
        }
        else if($this->isGranted('ROLE_CHAIN_MANAGER')){
            $truckRoutes = $truckRouteRepository->getAll();
        }
        
        return $this->render('truck_route/index.html.twig', [
            'truck_routes' => $truckRoutes
        ]);
    }

    /**
     * @Route("/new", name="truck_route_new", methods={"GET","POST"})
     */
    public function new(Request $request, TruckRouteRepository $truckRouteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');

        $user = $this->getUser()->getId();
        $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore();

        $truck_route = new TruckRoute();
        $form = $this->createForm(TruckRouteType::class, $truck_route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {     
            $truck_route->setStore($store);
            $truckRouteRepository->insert($truck_route);
            return $this->redirectToRoute('truck_route_index');
        }

 
        return $this->render('truck_route/new.html.twig', [
            'truck_route' => $truck_route,
            'form' => $form->createView(),
            'map' => "https://www.google.com/maps/dir///@6.8053093,79.9128169,13.44z/data=!4m2!4m1!3e0",
        ]);
    }

    /**
     * @Route("/{id}/edit", name="truck_route_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TruckRoute $truck_route, TruckRouteRepository $truckRouteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');

        $user = $this->getUser()->getId();
        $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore();

        $form = $this->createForm(TruckRouteType::class, $truck_route);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $truck_route->setStore($store);
            $truckRouteRepository->update($truck_route);
            
            return $this->redirectToRoute('truck_route_index');
        }
        
        return $this->render('truck_route/edit.html.twig', [
            'truck_route' => $truck_route,
            'form' => $form->createView(),
            'map' => $truck_route->getMap(),
        ]);
    }

    /**
     * @Route("/{id}", name="truck_route_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, TruckRouteRepository $truckRouteRepository): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');

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
