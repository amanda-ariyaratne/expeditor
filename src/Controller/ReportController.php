<?php

namespace App\Controller;

use App\Form\QuarterlySalesReportByProductType;
use App\Form\QSbyStore;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Purchase;
use App\Entity\Product;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/quarterly/sales/{year}", name="report_quarterly_sales", methods={"GET", "POST"})
     */
    public function getQuarterlySalesReport(Request $request, $year = null): Response
    {
        if ($year == null) {
            $year = date('Y');
        }
        $defaultData = [
            'year' => $year
        ];
        $form = $this->createForm(QuarterlySalesReportByProductType::class, $defaultData, [
            'entityManager' => $this->getDoctrine()->getManager(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('report_quarterly_sales', ['year' => $data['year']]);
        }
        $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesByProductReport($year);
        return $this->render('report/quarterly_sales_report.html.twig', [
            'form' => $form->createView(),
            'records' => $records
        ]);
    }

    /**
     * @Route("/quarterly-sales/store/{year}/{store}", name="report_quarterly_sales_store", methods={"GET", "POST"})
     */
    public function getQuarterlySalesByStoreReport(Request $request, $year = null, $store = null): Response
    {
        if ($year == null) {
            $year = date('Y');
        }
        $defaultData = [
            'year' => $year
        ];
        $form = $this->createForm(QSbyStore::class, $defaultData, [
            'entityManager' => $this->getDoctrine()->getManager(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('report_quarterly_sales_store', ['year' => $data['year'], 'store' => $data['store']]);
        }
        if ($store == null) {
            $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesByStoreReport($year);
        } else {
            // TODO
        }
        
        return $this->render('report/quarterly_sales_report_by_store.html.twig', [
            'form' => $form->createView(),
            'records' => $records
        ]);
    }

    /**
     * @Route("/popular/products", name="report_popular_products", methods={"GET"})
     */
    public function getPopularProducts(): Response
    {
        $records = $this->getDoctrine()->getRepository(Product::class)->getMostPopularProducts();
        
        return $this->render('report/popular_products.html.twig', [
            'records' => $records
        ]);
    }
}
