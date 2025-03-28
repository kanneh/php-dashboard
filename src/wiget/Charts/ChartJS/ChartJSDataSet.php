<?php
namespace Kanneh\PhpDashboard\Wiget\Charts\ChartJS;

use Kanneh\PhpDashboard\Wiget\Charts\ChartJS\PSSLChartJS;

class ChartJSDataSet {
    private $config = [];

    protected $defualtDataSetConfig = [
        "label"=>"",
        "data"=>[],
        //"parsing"=>[],
        //"backgroundColor"=>"",
        //"borderColor"=>"",
        //"borderWidth"=>1
    ];

    public function __construct(array $config){
        if(isset($config["dataStore"])){
            $data = $config["dataStore"]->data;
            unset($config["dataStore"]);
            if(isset($config["columns"])){
                $col = $config["columns"];
                $config['data'] = array_map(function($row) use($col){
                    return $row[$col[0]];
                },$data);
            }
        }
        $config = array_merge($this->defualtDataSetConfig, $config);
        $this->config = $config;
    }

    public function handle(PSSLChartJS $js){
        $js->addDataSet(array_merge($this->defualtDataSetConfig, $this->config));
        return $js;
    }
}