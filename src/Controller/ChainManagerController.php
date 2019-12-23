<?php

namespace App\Controller;

use App\Entity\ChainManager;
use App\Form\ChainManagerType;
use App\Repository\ChainManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\User;

/**
 * @Route("/chainManager")
 */
class ChainManagerController extends AbstractController
{
    /**
     * @Route("/", name="chain_manager_index", methods={"GET"})
     */
    public function index(ChainManagerRepository $chainManagerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        $managers = $this->getDoctrine() 
                        ->getRepository(ChainManager::class)
                        ->getAll();
        return $this->render('chain_manager/index.html.twig', [
            'managers' => $managers
        ]);
    }

    /**
     * @Route("/new", name="chain_manager_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        $chainManager = new ChainManager();
        
        $form = $this->createForm(ChainManagerType::class, $chainManager, [
            'validation_group' => 'new',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $storeManager = $form->getData();

            /* process password */
            $plain_password = $chainManager->getUser()->getPassword();
            $hashed_password = $passwordEncoder->encodePassword($chainManager->getUser(), $plain_password);
            $chainManager->getUser()->setPassword($hashed_password);

            $chainManager->getUser()->setRoles(['ROLE_CHAIN_MANAGER']);

            
            $entityManager = $this->getDoctrine()->getRepository(ChainManager::class)->insert($chainManager);

            return $this->redirectToRoute('chain_manager_index');

        }
        return $this->render('chain_manager/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chain_manager_show", methods={"GET"})
     */
    public function show(ChainManager $chainManager): Response
    {
        return $this->render('chain_manager/show.html.twig', [
            'chain_manager' => $chainManager,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chain_manager_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        $chainManager = $this->getDoctrine() 
                             ->getRepository(ChainManager::class)
                             ->getById($id);
        
        $form = $this->createForm(ChainManagerType::class, $chainManager, [
            'validation_group' => 'edit',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chainManager = $form->getData();

            $chainManager->getUser()->setRoles(['ROLE_CHAIN_MANAGER']);

            
            
            

            $entityManager = $this->getDoctrine()->getRepository(ChainManager::class)->update($chainManager);

            return $this->redirectToRoute('chain_manager_index');

        }
        return $this->render('chain_manager/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chain_manager_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        if ($this->isCsrfTokenValid('chain_manager', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $chainManager = $entityManager->getRepository(ChainManager::class)->deleteById($id);
            return new JsonResponse([
                'status' => 'true'
            ]);
        }
        
        return new JsonResponse([
            'status' => 'false'
        ]);
    }
}
