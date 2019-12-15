<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $stores = $storeRepository->findAll();
        // dd($stores);
        return $this->render('store/index.html.twig', [
            'stores' => $stores,
        ]);
    }

    /**
     * @Route("/new", name="store_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('store_index');
        }

        return $this->render('store/new.html.twig', [
            'store' => $store,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="store_show")
     */
    public function show(Store $store): Response
    {           
        return $this->render('store/show.html.twig', [
            'stores' => $store,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="store_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Store $store): Response
    {
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('store_index');
        }

        return $this->render('store/edit.html.twig', [
            'store' => $store,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="store_delete")
     */
    public function delete(Request $request, $id, StoreRepository $storeRepository): Response
    {   
        $store = $storeRepository->findById($id);

        if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($store);
            $entityManager->flush();
        }

        return $this->redirectToRoute('store_index');
    }
}
