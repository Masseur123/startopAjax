<?php
    use \koolphp\koolreport\widgets\koolphp\Table;
use Monolog\Formatter\TestFooNorm;

?>
<div class="report-content">
    <div class="text-center">
        <h1>Minimum Settings</h1>
        <p class="lead">Minimum settings to get KoolPHP Table working</p>
    </div>
    <?php
    Table::create(array(
        "dataSource"=>$this->dataStore('data')
    ));
    ?>
</div>