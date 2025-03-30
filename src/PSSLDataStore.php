<?php
namespace Kanneh\PhpDashboard;

class PSSLDataStore
{
    public $name;
    public $data = [];

    public function __construct($name)
    {
        $this->name = $name;
    }
    
    function handle(PSSLReport $report){
        $this->data = $report->getData();
        $report->addStore($this->name,$this);
        return $report;
    }

    public function get($key=""){
        if($key){
            return array_column($this->data,$key);
        }
        return $this->data;
    }
}