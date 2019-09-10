<?php

namespace App\Controller;

use App\Repository\TransitRepository;
use App\Repository\BatchRepository;

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\Expression\Binary\MatchesBinary;

/**
 * Controller used to manage dashboard.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("{_locale}/dashboard", methods={"GET", "POST"}, name="transit_dashboard")
     */
    public function transitDashboard(TransitRepository $transitRepo, BatchRepository $batchRepo): Response
    {
        // Total transit file open 
        // $transitFileOpen = $transitRepo->findBy(['is_open' => true]);
        $transitFileOpen = $transitRepo->findByIsOpen(true);

        // Total transit file close
        $transitFileClose = $transitRepo->findByIsOpen(false);

        // Total booking  
        $totalBatch = $batchRepo->findAllCount();

        // Four last transit file 
        $fourLastTransitFiles = $transitRepo->findByJoin();

        // dump($fourLastTransitFiles); exit;

        return $this->render('dashboard/transit.html.twig', [
            'transitFileOpen' => $transitFileOpen,
            'transitFileClose' => $transitFileClose,
            'totalBatch' => $totalBatch,
            'fourLastTransitFiles' => $fourLastTransitFiles,
        ]);
    }
}
