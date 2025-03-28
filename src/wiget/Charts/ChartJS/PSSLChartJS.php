<?php
namespace Kanneh\PhpDashboard\Wiget\Charts\ChartJS;

class PSSLChartJS{
    protected $defualtConfig = [
        "type"=>'line',
        "data"=>[],
        "options"=>[],
        "plugins"=> []
    ];

    protected $defualtDataConfig = [
        "labels"=>[],
        "datasets"=>[]
    ];

    public $config = [];
    public $id;

    public function __construct($configurations=[]){
        $this->config = array_merge($this->defualtConfig,$configurations);
        if(!isset($configurations['options'])){
            unset($this->config['options']);
        }
        if(!isset($configurations['plugins'])){
            unset($this->config['plugins']);
        }
        $this->id = "PSSLCHARTJS".random_int(100000000,999999999);
    }

    public function render(){
        print_r("<canvas id='{$this->id}'></canvas>");
        print_r("<script>");
        print_r("window.addEventListener('load',function() {");
        print_r("var ctx = document.getElementById('{$this->id}');");
        print_r("var myChart = new Chart(ctx,".json_encode($this->config).");");
        print_r("});");
        print_r("</script>");
    }

    public static function create($config){
        $chart = new PSSLChartJS($config);
        print_r("<canvas id='{$chart->id}'></canvas>");
        return $chart;
    }
    public static function createRender($config){
        $chart = new PSSLChartJS($config);
        print_r("<canvas id='{$chart->id}'></canvas>");
        $chart->render();
    }

    public function pipe(object $instance)
    {
        if (is_callable([$instance, 'handle'])) {
            $instance->handle($this);
        }
        return $this;
    }
    public function setType(string $type)
    {
        $this->config['type'] = $type;
        return $this;
    }
    public function setLabels(array $labels)
    {
        $this->config['data']['labels'] = $labels;
        return $this;
    }

    public function addDataSet(array $data){
        $this->config['data']['datasets'][] = $data;
        return $this;
    }



}