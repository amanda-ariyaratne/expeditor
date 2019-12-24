<?php

namespace App\Controller;


use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Address;
use App\Entity\ContactNo;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/login", name="customer_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('customer/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="customer_register" , methods={"GET","POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $customer = new Customer();
        
		$form = $this->createForm(CustomerType::class, $customer);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$customer = $form->getData();

            $plain_password = $customer->getUser()->getPassword();
            $hashed_password = $passwordEncoder->encodePassword($customer->getUser(), $plain_password);
            $customer->getUser()->setPassword($hashed_password);
            $customer->getUser()->setRoles(['ROLE_CUSTOMER']);

			$entityManager = $this->getDoctrine()->getRepository(Customer::class)->insert($customer);
            //$this->login();
			return $this->redirectToRoute('productList');
		}

		return $this->render('customer/register.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);


    }


    /**
     * @Route("/getCustomerById", name="get_customer")
     */
    public function getCustomerById($id): Response
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->getById($id);
        return $customer;
    }

    /**
     * @Route("/", name="customer_index", methods={"GET"})
     */
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="customer_show", methods={"GET"})
     */
    public function show($id): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' =>  $this->getDoctrine()->getRepository(Customer::class)->getById($id),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="customer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     */
    // public function delete(Request $request, Customer $customer): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($customer);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('customer_index');
    // }
}
