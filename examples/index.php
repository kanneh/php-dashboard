<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Kanneh\PhpDashboard\PDOMYSQL;
use Kanneh\PhpDashboard\PSSLReport;
use Kanneh\PhpDashboard\PSSLDataStore;
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
        $this->src("mysql")->fetch("SELECT * FROM purchaseitems")
        ->pipe(new PSSLDataStore("companies"));
    }
}

$report = new MyReport();

PSSLTable::create([
    "data"=>$report->dataStore("companies"),
    
    "css"=>"table table-striped table-bordered"
]);