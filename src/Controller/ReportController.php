<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/reports")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/quarterly/sales", name="report_quarterly_sales", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('reports/quaterly_sales.html.twig');
    }
}
