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
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        $trainTrip = new TrainTrip();
        
        $form = $this->createForm(TrainTripType::class, $trainTrip);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getRepository(TrainTrip::class)->insert($trainTrip);
            return $this->redirectToRoute('train_trip_index');
        }
        return $this->render('train_trip/new.html.twig', [
            'train_trip' => $trainTrip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="train_trip_index", methods={"GET"})
     */
    public function index(TrainTripRepository $truckRouteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');

        $truckRoutes = $truckRouteRepository->getAll();

        return $this->render('train_trip/index.html.twig', [
            'train_trips' => $truckRoutes,
        ]);
    }    

    /**
     * @Route("/{id}/edit", name="train_trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TrainTrip $trainTrip): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');

        $form = $this->createForm(TrainTripType::class, $trainTrip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getRepository(TrainTrip::class)->update($trainTrip);
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
    public function delete(Request $request, $id, TrainTripRepository $trainTripRepository): Response
    {   
        $deleted = false;
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');

        if ($this->isCsrfTokenValid('train_trip', $request->request->get('_token'))) {
            
            $deleted = $trainTripRepository->delete($id);
            return new JsonResponse([
                'status' => $deleted
            ]);
        }

        return new JsonResponse(['status' => $deleted]);
    }
}