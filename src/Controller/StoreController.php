<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/store")
 */
class StoreController extends AbstractController
{
    /**
     * @Route("/", name="store_index")
     */
    public function index(StoreRepository $storeRepository): Response
    {
        $stores = $storeRepository->getAll();
        return $this->render('store/index.html.twig', [
            'stores' => $stores,
        ]);
    }

    /**
     * @Route("/new", name="store_new", methods={"GET","POST"})
     */
    public function new(Request $request, StoreRepository $storeRepository): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $store =  $form->getData();
            $storeRepository->insert($store);
            return $this->redirectToRoute('store_index');
        }

        return $this->render('store/new.html.twig', [
            'store' => $store,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="store_show", requirements={"id"="\d+"})
     */
    public function show(Store $store): Response
    {           
        return $this->render('store/show.html.twig', [
            'stores' => $store,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="store_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Store $store, StoreRepository $storeRepository): Response
    {
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);
        // dd($store);

        if ($form->isSubmitted() && $form->isValid()) {
            $storeRepository->update($store);
            return $this->redirectToRoute('store_index');
        }

        return $this->render('store/edit.html.twig', [
            'store' => $store,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="store_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, StoreRepository $storeRepository): Response
    {   
        $deleted = false;
        if ($this->isCsrfTokenValid('store-token', $request->request->get('_token'))) {
            
            $deleted = $storeRepository->delete($id);
            return new JsonResponse([
                'status' => $deleted
            ]);
        }

        return new JsonResponse(['status' => $deleted]);
    }
}
