<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLReport;

class PSSLSort{
    public $sorts;
    public $data = [];

    public function __construct($config=[])
    {
        $this->sorts = $config;
    }
    
    function handle(PSSLReport $report){
        $this->data = $report->getData();
        for($i = 0; $i < count($this->sorts); $i++){
            $sort = $this->sorts[$i];
            $key = $sort[0];
            if(count($sort) == 1){
                usort($this->data, function ($a, $b) use ($key) {
                    return $a[$key] <=> $b[$key];
                });
            }else if(count($sort) == 2){
                if($sort[1] == "desc"){
                    usort($this->data, function ($a, $b) use ($key) {
                        return $b[$key] <=> $a[$key];
                    });
                }else{
                    usort($this->data, function ($a, $b) use ($key) {
                        return $a[$key] <=> $b[$key];
                    });
                }
            }
        }
        $report->setData($this->data);
        return $report;
    }
}