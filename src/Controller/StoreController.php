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
     * @Route("/delete/{id}", name="store_delete")
     */
    public function delete(Request $request, $id, StoreRepository $storeRepository): Response
    {   
        $storeRepository->delete($id);
        // if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->request->get('_token'))) {
        //     $storeRepository->delete($id);
        // }

        return $this->redirectToRoute('store_index');
    }
}
