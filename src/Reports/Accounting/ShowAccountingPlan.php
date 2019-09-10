<?php

namespace App\Reports\Accounting;

//use \koolreport\processes\Group;
use \koolreport\processes\Sort;
//use \koolreport\processes\Limit;


class ShowAccountingPlan extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;

    function settings()
    {
        return array(
            "dataSources" => array(
                "startop" => array(
                    "connectionString" => "mysql:host=localhost;dbname=startop",
                    "username" => "root",
                    "password" => "nedenouveau57",
                    "charset" => "utf8"
                ),
            )
        );
    }

    public function setup()
    {
        $this->src('startop')
            ->query( "select a.number, a.title from act_account a")
            /*->pipe(new Sort(array(
                "type" => "EXPENSE"
            )))
            ->pipe(new CalculatedColumn(array(
                "tooltip" => "'{country} : $'.number_format({amount})",
            )))
            ->pipe(new ColumnMeta(array(
                "tooltip" => array(
                    "type" => "string",
                )
            )))*/
            ->pipe($this->dataStore("accounting_plan"));
    }
}
