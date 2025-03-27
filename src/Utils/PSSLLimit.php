<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLReport;

class PSSLLimit{
    public $offset;
    public $length;
    public $data = [];

    public function __construct($config=[])
    {
        $config = array_merge([
            "offset"=>0,
            "length"=>10
        ],$config);
        $this->offset = $config["offset"];
        $this->length = $config["length"];
    }
    
    function handle(PSSLReport $report){
        $this->data = array_slice($report->getData(),$this->offset,$this->length);
        $report->setData($this->data);
        return $report;
    }
}