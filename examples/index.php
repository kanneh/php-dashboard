<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Kanneh\PhpDashboard\PDOMYSQL;
use Kanneh\PhpDashboard\PSSLReport;
use Kanneh\PhpDashboard\PSSLDataStore;
use Kanneh\PhpDashboard\Utils\PSSLLimit;
use Kanneh\PhpDashboard\Utils\PSSLSort;
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
        $this->src("mysql")->fetch("SELECT * FROM stocklevel")
        ->pipe(new PSSLSort([["stocklevel"]]))
        ->pipe(new PSSLLimit([
            "length"=>10
        ]))
        ->pipe(new PSSLDataStore("companies"));
    }
}

$report = new MyReport();

PSSLTable::create([
    "data"=>$report->dataStore("companies"),
    
    "css"=>"table table-striped table-bordered",
    "dataTable"=>[]
]);