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
use App\Entity\Product;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/quarterly/sales/{year}/{category}", name="report_quarterly_sales", methods={"GET", "POST"})
     */
    public function getQuarterlySalesReport(Request $request, $year = null, $category = null): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');
        
        if ($year == null) {
            $year = date('Y');
        }
        if ($category == null) {
           $category = 'product';
        }
        $defaultData = [
            'year' => $year,
            'categorize_by' => $category
        ];
        
        $form = $this->createForm(QuarterlySalesReportType::class, $defaultData, [
            'entityManager' => $this->getDoctrine()->getManager()
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('report_quarterly_sales', ['year' => $data['year'], 'category' => $data['categorize_by']]);
        }

        if ($category == 'product') {
            $cat = 'p';
            $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesByProductReport($year);
        } else if ($category == 'store') {
            $cat = 's';
            $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesByStoreReport($year);
        } else {
            $cat = 't';
            $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesByRouteReport($year);
        }

        return $this->render('report/quarterly_sales_report.html.twig', [
            'form' => $form->createView(),
            'records' => $records,
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/popular/products", name="report_popular_products", methods={"GET"})
     */
    public function getPopularProducts(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');

        $records = $this->getDoctrine()->getRepository(Product::class)->getMostPopularProducts();
        $totalOrders = array_sum(array_column($records, 'cnt'));
        $percentage1 = number_format(($records[0]['cnt']/$totalOrders)*100, 2, '.', '');
        $percentage2 = number_format(($records[1]['cnt']/$totalOrders)*100, 2, '.', '');
        $percentage3 = number_format(($records[2]['cnt']/$totalOrders)*100, 2, '.', '');
        //dd($percentage1);
        return $this->render('report/popular_products.html.twig', [
            'records' => $records,
            'percentage1' => $percentage1,
            'percentage2' => $percentage2,
            'percentage3' => $percentage3
        ]);
    }


    /**
     * @Route("/customerOrder/{id}")
     */
    public function getCustomerOrderReport($id=0 , Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHAIN_MANAGER');

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
