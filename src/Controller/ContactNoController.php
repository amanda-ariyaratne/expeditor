<?php

namespace App\Controller;

use App\Entity\ContactNo;
use App\Form\ContactNoType;
use App\Repository\ContactNoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact/no")
 */
class ContactNoController extends AbstractController
{
    /**
     * @Route("/", name="contact_no_index", methods={"GET"})
     */
    public function index(ContactNoRepository $contactNoRepository): Response
    {
        return $this->render('contact_no/index.html.twig', [
            'contact_nos' => $contactNoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="contact_no_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contactNo = new ContactNo();
        $form = $this->createForm(ContactNoType::class, $contactNo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactNo);
            $entityManager->flush();

            return $this->redirectToRoute('contact_no_index');
        }

        return $this->render('contact_no/new.html.twig', [
            'contact_no' => $contactNo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_no_show", methods={"GET"})
     */
    public function show(ContactNo $contactNo): Response
    {
        return $this->render('contact_no/show.html.twig', [
            'contact_no' => $contactNo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contact_no_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContactNo $contactNo): Response
    {
        $form = $this->createForm(ContactNoType::class, $contactNo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_no_index');
        }

        return $this->render('contact_no/edit.html.twig', [
            'contact_no' => $contactNo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_no_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContactNo $contactNo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactNo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contactNo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_no_index');
    }
}
