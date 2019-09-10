<?php

namespace App\Controller\Report;

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

/**
 * Controller used to manage budget reports in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class ReportBudgetController extends AbstractController
{
    /**
     * @Route("/report/budget-transaction", name="budget_transaction")
     */
    public function showBudgetTransaction(): response
    {
        $showBudgetTransaction = new \App\Reports\Budget\ShowAccountingEntry();

        return new Response(
            $showBudgetTransaction->run()->render(true)
        );
    }
}
