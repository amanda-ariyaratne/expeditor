<?php

namespace App\Controller;

use App\Entity\DriverAssistant;
use App\Form\DriverAssistantType;
use App\Repository\DriverAssistantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Store;

/**
 * @Route("/driver/assistant")
 */
class DriverAssistantController extends AbstractController
{
    /**
     * @Route("/", name="driver_assistant_index", methods={"GET"})
     */
    public function index(DriverAssistantRepository $driverAssistantRepository): Response
    {
        $assistants = $this->getDoctrine() 
                        ->getRepository(DriverAssistant::class)
                        ->getAll();
        return $this->render('driver_assistant/index.html.twig', [
            'assistants' => $assistants
        ]);
    }

    /**
     * @Route("/new", name="driver_assistant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $driverAssistant = new DriverAssistant();

        $form = $this->createForm(DriverAssistantType::class, $driverAssistant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getRepository(DriverAssistant::class)->insert($driverAssistant);

            return $this->redirectToRoute('driver_assistant_index');
        }

        return $this->render('driver_assistant/new.html.twig', [
            'driver_assistant' => $driverAssistant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="driver_assistant_show", methods={"GET"})
     */
    public function show(DriverAssistant $driverAssistant): Response
    {
        return $this->render('driver_assistant/show.html.twig', [
            'driver_assistant' => $driverAssistant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="driver_assistant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id): Response
    {
        $driverAssistant = $this->getDoctrine() 
                             ->getRepository(DriverAssistant::class)
                             ->getById($id);

        $form = $this->createForm(DriverAssistantType::class, $driverAssistant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $driverAssistant = $form->getData();

            $entityManager = $this->getDoctrine()->getRepository(DriverAssistant::class)->update($driverAssistant);

            return $this->redirectToRoute('driver_assistant_index');

        }

        return $this->render('driver_assistant/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="driver_assistant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        if ($this->isCsrfTokenValid('driver_assistant', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $storeManager = $entityManager->getRepository(DriverAssistant::class)->deleteById($id);
            return new JsonResponse([
                'status' => 'true'
            ]);
        }
        
        return new JsonResponse([
            'status' => 'false'
        ]);
    }
}
