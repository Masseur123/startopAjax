<?php

namespace App\Controller;

use App\Reports\MyReport;

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

class ReportController extends AbstractController
{
    public function __contruct()
    {
        $this->middleware("guest");
    }

    /**
     * First Report.
     *
     * @Route("{_locale}/report/show", methods={"GET", "POST"}, name="report_show")
     *
     */
    public function index(): response
    {
        $report = new MyReport;
        $report->run();

        /*dump($report);
        exit;*/

        return $this->render('report/MyReport.html.php', ['report' => $report]);
    }
}
