<?php

namespace App\Controller;

use App\Form\QuarterlySalesReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Store;

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
}
