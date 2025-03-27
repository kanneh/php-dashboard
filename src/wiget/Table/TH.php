<?php
namespace Kanneh\PhpDashboard\Wiget\Table;

class TH{
    public $name;
    public $label;
    public $visible;
    public static function create($config){
        $config = array_merge([
            "label"=>isset($config["label"])?$config["label"]:(isset($config["name"])?$config["name"]:""),
            "css"=>"text-center",
            "name"=>isset($config["name"])?$config["name"]:(isset($config["label"])?str_replace(" ","_",$config["label"]):""),
            "visible"=>true
        ],$config);
        $th = new TH();
        $th->name = $config['name'];
        $th->label = $config['label'];
        $th->visible = $config['visible'];

        if($config['visible']){
            print_r("<th class='{$config['css']}'>{$config['label']}</th>");
        }
        return $th;
    }
}