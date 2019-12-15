<?php

namespace App\Controller;

use App\Entity\StoreManager;
use App\Form\StoreManagerType;
use App\Repository\StoreManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\User;
use App\Entity\Store;

/**
 * @Route("/store/manager")
 */
class StoreManagerController extends AbstractController
{
    /**
     * @Route("/", name="store_manager_index", methods={"GET"})
     */
    public function index(StoreManagerRepository $storeManagerRepository): Response
    {
        $managers = $this->getDoctrine() 
                        ->getRepository(StoreManager::class)
                        ->getAll();
        return $this->render('store_manager/index.html.twig', [
            'managers' => $managers
        ]);
    }

    /**
     * @Route("/new", name="store_manager_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $storeManager = new StoreManager();
        
        $form = $this->createForm(StoreManagerType::class, $storeManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $storeManager = $form->getData();

            /* process password */
            $plain_password = $storeManager->getUser()->getPassword();
            $hashed_password = $passwordEncoder->encodePassword($storeManager->getUser(), $plain_password);
            $storeManager->getUser()->setPassword($hashed_password);

            $storeManager->getUser()->setRoles(['ROLE_STORE_MANAGER']);

            /* set store */
            $storeId = $form->get('store_id')->getData();
            $store = $this->getDoctrine()->getRepository(Store::class)->getById($storeId);
            $storeManager->setStore($store);

            $entityManager = $this->getDoctrine()->getRepository(StoreManager::class)->insert($storeManager);

            return $this->redirectToRoute('store_manager_index');

        }
        return $this->render('store_manager/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="store_manager_show", methods={"GET"})
     */
    public function show(StoreManager $storeManager): Response
    {
        return $this->render('store_manager/show.html.twig', [
            'store_manager' => $storeManager,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="store_manager_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StoreManager $storeManager): Response
    {
        $form = $this->createForm(StoreManagerType::class, $storeManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('store_manager_index');
        }

        return $this->render('store_manager/edit.html.twig', [
            'store_manager' => $storeManager,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="store_manager_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        if ($this->isCsrfTokenValid('delete-store-manager', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $storeManager = $entityManager->getRepository(StoreManager::class)->deleteById($id);
            return new JsonResponse([
                'status' => 'true'
            ]);
        }
        
        return new JsonResponse([
            'status' => 'false'
        ]);
    }
}
