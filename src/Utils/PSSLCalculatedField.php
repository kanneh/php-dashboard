<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLReport;

class PSSLCalculatedField{
    public $name;
    public $formula;
    public $data = [];

    public function __construct($config=[])
    {
        $config = array_merge([
            "name"=>"",
            "formula"=>""
        ],$config);
        $this->name = $config["name"];
        $this->formula = $config["formula"];
    }
    
    function handle(PSSLReport $report){
        if(is_callable($this->formula)){
            $this->data = array_map($this->formula,$report->getData());
        }else{
            preg_match_all('/\{(.*?)\}/', $this->formula, $matches);
            $parts = $matches[1];
            foreach ($parts as $part) {
                $this->formula = str_replace("{" . $part . "}", '$' . $part, $this->formula);
            }
            // print_r($parts);
            // print_r($this->formula);
            $this->data = array_map(function($row) use($parts){
                // print_r($row);
                foreach ($parts as $part) {
                    $$part = $row[$part ];
                }
                $row[$this->name] = eval("return ".$this->formula.";");
                return $row;
            },$report->getData());
        }
        // print_r($this->data);
        $report->setData($this->data);
        return $report;
    }
}