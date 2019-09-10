<?php

namespace App\Reports\Budget;

//use \koolreport\processes\Group;
use \koolreport\processes\Sort;
//use \koolreport\processes\Limit;


class ShowBudgetTransaction extends \koolreport\KoolReport
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
            ->query( "select h.doing_at, h.description, h.amount, h.taxamount, h.status, b.code, c.title, a.number 
                        from bud_cost_center_hist h, se_branch b, bud_cost_center c, act_account a
                        where h.branch_id=b.id AND h.costcenter_id=c.id AND c.account_id=a.id")
            ->pipe(new Sort(array(
                "type" => "EXPENSE"
            )))
            /*->pipe(new CalculatedColumn(array(
                "tooltip" => "'{country} : $'.number_format({amount})",
            )))
            ->pipe(new ColumnMeta(array(
                "tooltip" => array(
                    "type" => "string",
                )
            )))*/
            ->pipe($this->dataStore("budget_transaction"));
    }
}
