<?php

namespace App\Reports\Transit;

//use \koolreport\processes\Group;
use \koolreport\processes\Sort;
//use \koolreport\processes\Limit;


class ShowTransitFile extends \koolreport\KoolReport
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
            ->query( "select t.reference,t.boat,t.comingfrom,t.goingto, c.name as origin 
                        from tra_transit t
                        INNER JOIN co_country c
                        ON t.countryfrom_id=c.id AND t.countryto_id=c.id")
            ->pipe(new Sort(array(
                "is_open" => 1
            )))
            /*->pipe(new CalculatedColumn(array(
                "tooltip" => "'{country} : $'.number_format({amount})",
            )))
            ->pipe(new ColumnMeta(array(
                "tooltip" => array(
                    "type" => "string",
                )
            )))*/
            ->pipe($this->dataStore("transit_file"));
    }
}
