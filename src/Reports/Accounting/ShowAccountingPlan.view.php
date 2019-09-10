<?php
use \koolreport\widgets\koolphp\Table;
use Monolog\Formatter\TestFooNorm;

?>

<div class="report-content">
    <div class="text-center">
        <h1>Accounting Plan</h1>
    </div>

    <?php
    Table::create(array(
        "dataStore" => $this->dataStore('accounting_plan'),
        "columns" => array(
            "number" => array(
                "label" => "Account Code"
            ),
            "title" => array(
                "label" => "Account Denomination"
            )
        ),
        "cssClass" => array(
            "table" => "table table-bordered table-striped"
        )
    ));
    ?>
</div>