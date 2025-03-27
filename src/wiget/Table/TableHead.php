<?php
namespace Kanneh\PhpDashboard\Wiget\Table;



class TableHead{
    private $table;
    public $cells;
    public static function create($config,$tb){
        foreach($config as $key=>$value){
            if(empty($value)){
                $config[$key] = [
                    "label"=>$key,
                    "name"=>$key
                ];
            }else{
                if(is_string($value)){
                    $val = [];
                    unset($config[$key]);
                    $key = $value;
                    $val['label'] = $value;
                    $value = $val;
                    // $config[$key] = $value;
                }
                $value['name'] = $key;
                $config[$key] = $value;
            }
        }

        // print_r($config);

        $tbh = new TableHead();
        $tbh->table = $tb;
        print_r("<thead>");
        print_r("<tr>");
        foreach($config as $column){
            $cll = TH::create($column);
            $tbh->addCell($cll);
        }
        print_r("</tr>");
        print_r("</thead>");
        return $tbh;
    }

    public function addCell($column){
        $this->cells[] = $column;
    }
}