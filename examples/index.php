<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Kanneh\PhpDashboard\Utils\PSSLCalculatedField;
use Kanneh\PhpDashboard\Utils\PSSLCalculatedFields;
use Kanneh\PhpDashboard\Utils\PSSLGroup;
use Kanneh\PhpDashboard\Wiget\Charts\ChartJS\ChartJSDataSet;
use Kanneh\PhpDashboard\PDOMYSQL;
use Kanneh\PhpDashboard\PSSLReport;
use Kanneh\PhpDashboard\PSSLDataStore;
use Kanneh\PhpDashboard\Utils\PSSLLimit;
use Kanneh\PhpDashboard\Utils\PSSLSort;
use Kanneh\PhpDashboard\Wiget\Charts\ChartJS\PSSLChartJS;
use Kanneh\PhpDashboard\Wiget\Table\PSSLTable;

class MyReport extends PSSLReport
{
    public function settings(){
        return [
            "dataSources"=>array(
                "mysql"=>array(
                    "connectionString"=>"mysql:host=localhost;dbname=psslerp",
                    "username"=>'psslerp',
                    "password"=>'psslerp5'
                ),
            )
        ];
    }
    public function setUp()
    {
        $this->src("mysql")->fetch("SELECT i.title AS item,s.stocklevel FROM stocklevel s left join items i on s.item = i.id")
        ->pipe(new PSSLSort([["stocklevel"]]))
        ->pipe(new PSSLLimit([
            "length"=>10
        ]))
        ->pipe(new PSSLDataStore("companies"));

        $srcInvoices = $this->src("mysql")->fetch("SELECT c.title AS company,i.customer,i.officer,i.invoicedate,ii.quantity,ii.unitprice,it.title AS product,i.id AS invoiceid FROM invoiceitems ii LEFT JOIN invoices i on ii.invoiceid = i.id LEFT JOIN items it ON ii.item = it.id LEFT JOIN companies c ON i.company = c.id");

        $srcInvoicesMod = $srcInvoices
        ->pipe(new PSSLCalculatedField([
            "name"=>"total",
            "formula"=>"{quantity}*{unitprice}"
        ]))
        ->pipe(new PSSLCalculatedFields([
            "monthName"=>"date('F_Y',strtotime({invoicedate}))",
            "Year"=>"date('Y',strtotime({invoicedate}))"
        ]))
        ->pipe(new PSSLDataStore("invoices"));

        $srcInvoicesMod
        ->pipe(new PSSLGroup([
            [
                ["min_total","min","total"],
                ["max_total","max","total"],
                ["sum_total","sum","total"],
                ["count_total","count","total"],
                ["avg_total","avg","total"],
            ],['officer','company','invoiceid']
        ]))
        ->pipe(new PSSLDataStore("invoicesbyofficersbyinvoice"));

    }
}

$report = new MyReport();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/examples/bootstrape.css">
        <link rel="stylesheet" href="/examples/datatables.min.css">
    </head>
    <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div style="height:300px;" >
                <?php
                PSSLChartJS::create([
                    "type"=>"bar",
                    "data"=>[
                        // "labels"=>[
                        //     "January",
                        //     "February"
                        // ],
                        "datasets"=>[
                            
                        ]
                    ]
                ])
                ->setType("line")
                ->addDataSet([
                    "label"=>"Added Directly",
                    "data"=>[6]
                ])
                ->pipe(new ChartJSDataSet([
                    "label"=>"Added Via API",
                    "data"=>[7,5,6,6,8]
                ]))
                ->pipe(new ChartJSDataSet([
                    "label"=>"Added Via API From DBN",
                    "dataStore"=>$report->dataStore("companies"),
                    "columns"=>[
                        "stocklevel"
                    ],
                    
                ]))
                ->setLabels($report->dataStore("companies")->get("item"))
                ->render()
                 ?>
                </div>
            </div>
            <div class="col-md-6">
                <div style="height:300px;" >
                <?php
                PSSLChartJS::create([
                    "type"=>"bar",
                    "data"=>[
                        // "labels"=>[
                        //     "January",
                        //     "February"
                        // ],
                        "datasets"=>[
                            
                        ]
                    ]
                ])
                ->setType("line")
                ->pipe(new ChartJSDataSet([
                    "label"=>"Min Total By Invoice",
                    "dataStore"=>$report->dataStore("invoicesbyofficersbyinvoice"),
                    "columns"=>[
                        "min_total"
                    ],
                    
                ]))
                ->setLabels($report->dataStore("invoicesbyofficersbyinvoice")->get("officer"))
                ->render()
                 ?>
                </div>
            </div>
        </div>
        <div class="mt-2 mb-2">
        <?php

PSSLTable::create([
    "data"=>$report->dataStore("invoicesbyofficersbyinvoice"),
    
    "css"=>"table table-striped table-bordered",
    "dataTable"=>[]
]);
?>
        </div>
        <div class="mt-2 mb-2">
        <?php

PSSLTable::create([
    "data"=>$report->dataStore("invoices"),
    
    "css"=>"table table-striped table-bordered",
    "dataTable"=>[
        "dom"=>"QBfltips"
    ]
]);
?>
        </div>
    <?php

PSSLTable::create([
    "data"=>$report->dataStore("companies"),
    
    "css"=>"table table-striped table-bordered",
    "dataTable"=>[]
]);
?>
    </div>
    <script src="/examples/jquery.js"></script>
    <script src="/examples/datatables.min.js"></script>
    <script src="/examples/bootstrape.js"></script>
    <script src="/examples/chart.min.js"></script>
    </body>
</html>