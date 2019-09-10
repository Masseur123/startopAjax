<?php

namespace App\Controller\Report;

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;


/**
 * Controller used to manage transit reports in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class ReportTransitController extends AbstractController
{
    /**
     * @Route("/report/transit-file", name="transit_file")
     */
    public function showTransitFile(): response
    {
        $showtransitfile = new \App\Reports\Transit\ShowTransitFile();

        return new Response(
            $showtransitfile->run()->render(true)
        );
    }
}
