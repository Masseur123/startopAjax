<?php

namespace App\Controller\Report;

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

/**
 * Controller used to manage accounting reports in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class ReportAccountingController extends AbstractController
{
    /**
     * @Route("/report/accounting-entry", name="accounting_entry")
     */
    public function showAccountingEntry(): response
    {
        $showAccountingEntry = new \App\Reports\Accounting\ShowAccountingEntry();

        return new Response(
            $showAccountingEntry->run()->render(true)
        );
    }

    /**
     * @Route("/report/accounting-plan", name="accounting_plan")
     */
    public function showAccountingPlan(): response
    {
        $showAccountingPlan = new \App\Reports\Accounting\ShowAccountingPlan();

        return new Response(
            $showAccountingPlan->run()->render(true)
        );
    }
}
