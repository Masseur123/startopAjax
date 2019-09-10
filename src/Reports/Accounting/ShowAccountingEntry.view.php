<?php
use \koolreport\widgets\koolphp\Table;
use Monolog\Formatter\TestFooNorm;

?>

<div class="report-content">
    <div class="text-center">
        <h1>Accounting Entries</h1>
    </div>

    <?php
    Table::create(array(
        "dataStore" => $this->dataStore('accounting_entry'),
        "columns" => array(
            "doing_at" => array(
                "label" => "Operation Date"
            ),
            "number" => array(
                "label" => "Account"
            ),
            "title" => array(
                "label" => "Account Denomination"
            ),
            "description" => array(
                "label" => "description"
            ),
            "reference" => array(
                "label" => "Reference"
            ),
            "debit" => array(
                "label" => "Debit"
            ),
            "credit" => array(
                "label" => "Credit"
            )
        ),
        "cssClass" => array(
            "table" => "table table-bordered table-striped"
        )
    ));
    ?>
</div>