<?php
use \koolreport\widgets\koolphp\Table;
use Monolog\Formatter\TestFooNorm;

?>

<div class="report-content">
    <div class="text-center">
        <h1>Budget Transaction</h1>
    </div>

    <?php
    Table::create(array(
        "dataStore" => $this->dataStore('budget_transaction'),
        "columns" => array(
            "doing_at" => array(
                "label" => "Transaction Date"
            ),
            "title" => array(
                "label" => "Cost Center Code"
            ),
            "number" => array(
                "label" => "Account"
            ),
            "description" => array(
                "label" => "description"
            ),
            "code" => array(
                "label" => "Branch"
            ),
            "amount" => array(
                "label" => "Transaction Amount"
            )
        ),
        "cssClass" => array(
            "table" => "table table-bordered table-striped"
        )
    ));
    ?>
</div>