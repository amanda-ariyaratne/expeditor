<?php

namespace App\Controller;

use App\Entity\Truck;
use App\Form\TruckType;
use App\Repository\TruckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/truck")
 */
class TruckController extends AbstractController
{
    /**
     * @Route("/", name="truck_index", methods={"GET"})
     */
    public function index(TruckRepository $truckRepository): Response
    {
        $trucks = $this->getDoctrine() 
                        ->getRepository(Truck::class)
                        ->getAll();
        return $this->render('truck/index.html.twig', [
            'trucks' => $trucks
        ]);
    }

    /**
     * @Route("/new", name="truck_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $truck = new Truck();

        $form = $this->createForm(TruckType::class, $truck);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getRepository(Truck::class)->insert($truck);

            return $this->redirectToRoute('truck_index');
        }

        return $this->render('truck/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="truck_show", methods={"GET"})
     */
    public function show(Truck $truck): Response
    {
        return $this->render('truck/show.html.twig', [
            'truck' => $truck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="truck_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id): Response
    {
        $truck = $this->getDoctrine() 
                        ->getRepository(Truck::class)
                        ->getById($id);

        $form = $this->createForm(TruckType::class, $truck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $truck = $form->getData();

            $entityManager = $this->getDoctrine()->getRepository(Truck::class)->update($truck);

            return $this->redirectToRoute('truck_index');

        }

        return $this->render('truck/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="truck_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        if ($this->isCsrfTokenValid('truck', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $truck = $entityManager->getRepository(Truck::class)->deleteById($id);
            return new JsonResponse([
                'status' => 'true'
            ]);
        }
        
        return new JsonResponse([
            'status' => 'false'
        ]);
    }
}
