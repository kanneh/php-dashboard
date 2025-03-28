<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLReport;

class PSSLCalculatedFields{
    public $fields = [];
    public $data = [];

    public function __construct($config=[])
    {
        $this->fields = $config;
    }
    
    function handle(PSSLReport $report){
        $this->data = $report->getData();
        foreach($this->fields as $fieldname => $fieldformula){
            new PSSLCalculatedField([
                "name"=>$fieldname,
                "formula"=>$fieldformula
            ])->handle($report);
        }
        // print_r($report->getData());
        return $report;
    }
}