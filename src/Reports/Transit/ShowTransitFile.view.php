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
        "dataStore" => $this->dataStore('transit-file'),
        "columns" => array(
            "reference" => array(
                "label" => "Reference"
            ),
            "boat" => array(
                "label" => "Boat"
            ),
            "origin" => array(
                "label" => "Origin Country"
            ),
            "comingfrom" => array(
                "label" => "Origin Town"
            ),
            "origin" => array(
                "label" => "Destination Country"
            ),
            "goingto" => array(
                "label" => "Destination Town"
            )
        ),
        "cssClass" => array(
            "table" => "table table-bordered table-striped"
        )
    ));
    ?>
</div>