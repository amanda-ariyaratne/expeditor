<?php

namespace App\Controller;

use App\Form\QuarterlySalesReportType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Store;
use App\Entity\Purchase;
use App\Entity\Customer;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/quarterly/sales", name="report_quarterly_sales", methods={"GET"})
     */
    public function getQuarterlySalesReport(Request $request): Response
    {
        $defaultData = [];
        $form = $this->createForm(QuarterlySalesReportType::class, $defaultData, [
            'entityManager' => $this->getDoctrine()->getManager(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }
        return $this->render('report/quarterly_sales_report.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/customerOrder/{id}")
     */
    public function getCustomerOrderReport($id , Request $request): Response
    {
        //customer details
        $customers = $this->getDoctrine()->getRepository(Customer::class)->getAll();

        //purchase details
        $purchases = $this->getDoctrine()->getRepository(Purchase::class)->getDetailsByCustomerID($id);
        $id_arr = array();
        foreach($purchases as $p){
            if(!in_array($p["id"] , $id_arr)){
                array_push($id_arr , $p["id"]);
            }
        }
        $purchase_arr  = array();
        foreach($id_arr as $id){
            $purchase = array();
            foreach($purchases as $p){
                if($id == $p["id"]){
                    array_push($purchase, $p);
                }
            }
            array_push($purchase_arr , $purchase);
        }

        return $this->render('report/customer_order_report.html.twig', [
            'purchases' => $purchase_arr,
            'customers' =>  $customers,
        ]);
    }
}
