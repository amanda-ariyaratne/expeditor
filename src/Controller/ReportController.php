<?php

namespace App\Controller;

use App\Form\QuarterlySalesReportType;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/quarterly/sales", name="report_quarterly_sales")
     */
    public function getQuarterlySalesReport(Request $request): Response
    {
        $defaultData = [];
        $form = $this->createForm(QuarterlySalesReportType::class, $defaultData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }
        return $this->render('report/quarterly_sales_report.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
