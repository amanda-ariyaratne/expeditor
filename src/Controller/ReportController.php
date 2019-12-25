<?php

namespace App\Controller;

use App\Form\QuarterlySalesReportType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Purchase;

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
        $form = $this->createForm(QuarterlySalesReportType::class, $defaultData, [
            'entityManager' => $this->getDoctrine()->getManager(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('report_quarterly_sales', ['year' => $data['year']]);
        }
        $records = $this->getDoctrine()->getRepository(Purchase::class)->getQuarterlySalesReport($year);
        return $this->render('report/quarterly_sales_report.html.twig', [
            'form' => $form->createView(),
            'records' => $records
        ]);
    }
}
