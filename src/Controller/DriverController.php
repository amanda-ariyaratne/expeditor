<?php
namespace App\Controller;
use App\Entity\Driver;
use App\Entity\StoreManager;
use App\Form\DriverType;
use App\Repository\DriverRepository;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedHttpException;
/**
 * @Route("/driver")
 */
class DriverController extends AbstractController
{
    /**
     * @Route("/", name="driver_index", methods={"GET"})
     * IsGranted('ROLE_STORE_MANAGER')
     */
    public function index(DriverRepository $driverRepository): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_STORE_MANAGER', 'ROLE_CHAIN_MANAGER']);
        
        if ($this->isGranted('ROLE_STORE_MANAGER')){
            $user = $this->getUser()->getId();
            $store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();
            $drivers = $driverRepository->getAllByStore($store);
        }
        else if($this->isGranted('ROLE_CHAIN_MANAGER'))
        {
            $drivers = $driverRepository->getAll();
        }
        
        return $this->render('driver/index.html.twig', [
            'drivers' => $drivers,
        ]);
    }
    /**
     * @Route("/new", name="driver_new", methods={"GET","POST"})
     */
    public function new(Request $request, DriverRepository $driverRepositary, StoreRepository $storeRepositary): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');
        $driver = new Driver();
        $form = $this->createForm(DriverType::class, $driver);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) 
        {     
            $driverRepositary->insert($driver);
            return $this->redirectToRoute('driver_index');
        }
        return $this->render('driver/new.html.twig', [
            'driver' => $driver,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="driver_show", methods={"GET"})
     */
    public function show(Driver $driver): Response
    {
        return $this->render('driver/show.html.twig', [
            'driver' => $driver,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="driver_edit", methods={"GET","POST"})
     */
    
    public function edit($id, Request $request, Driver $driver, DriverRepository $driverRepositary, StoreRepository $storeRepositary): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_STORE_MANAGER', 'ROLE_CHAIN_MANAGER']);
        
        $user = $this->getUser()->getId();
        $user_store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();
        $driver_store = $this->getDoctrine()->getRepository(Driver::class)->find($id)->getStore()->getId();

        if ($user_store==$driver_store){

            $form = $this->createForm(DriverType::class, $driver);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $driverRepositary->update($driver);
                return $this->redirectToRoute('driver_index');
            }
            return $this->render('driver/edit.html.twig', [
                'driver' => $driver,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('driver_index');
    }
    /**
     * @Route("/delete/{id}", name="driver_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, DriverRepository $driverRepository): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_STORE_MANAGER');
        $deleted = false;

        $user = $this->getUser()->getId();
        $user_store = $this->getDoctrine()->getRepository(StoreManager::class)->find($user)->getStore()->getId();
        $driver_store = $this->getDoctrine()->getRepository(Driver::class)->find($id)->getStore()->getId();

        if ($user_store==$driver_store){

            if ($this->isCsrfTokenValid('driver-token', $request->request->get('_token'))) {
                
                $deleted = $driverRepository->delete($id);
                return new JsonResponse([
                    'status' => $deleted
                ]);
            }
            return new JsonResponse(['status' => $deleted]);

        }
        return $this->redirectToRoute('driver_index');

    }
}
