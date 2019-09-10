<?php

namespace App\Reports;

use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;


class SalesByCustomer extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;

    function settings()
    {
        return array(
            "dataSources" => array(
                "sales" => array(
                    "class" => '\koolreport\datasources\CSVDataSource',
                    "filePath" => dirname(__FILE__)."/customer_product_dollarsales2.csv",
                    "fieldSeparator" => ";"
                ),
            )
        );
    }

    function setup()
    {
        $this->src('sales')
            ->pipe(new Group(array(
                "by" => "customerName",
                "sum" => "dollar_sales"
            )))
            ->pipe(new Sort(array(
                "dollar_sales" => "desc"
            )))
            ->pipe(new Limit(array(10)))
            ->pipe($this->dataStore('sales_by_customer'));
    }
}
