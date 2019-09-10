<?php

namespace App\Controller;

// require_once dirname(__FILE__) . "/../Reports/SalesByCustomer.php";

use Monolog\Handler\LogEntriesHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;


class ExampleController extends AbstractController
{
    /**
     * @Route("/example/show", name="example_show")
     */
    public function show(): response
    {
        $salesbycustomer = new \App\Reports\SalesByCustomer;

        return new Response(
            $salesbycustomer->run()->render(true)
        );
    }
}
