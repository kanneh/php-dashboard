<?php
namespace Kanneh\PhpDashboard\Wiget\Table;

use Kanneh\PhpDashboard\Wiget\PSSLBaseWiget;


class PSSLTable extends PSSLBaseWiget{
    public $id;
    public $header;
    public $footer;
    public $body;



    public static function create(array $config = []){
        $config = array_merge([
            "data"=>[[]],
            "columns"=>[],
            "css"=>"table table-striped table-bordered"
        ],$config);
        // print_r($config);
        if($config["data"] instanceof \Kanneh\PhpDashboard\PSSLDataStore){
            $config["data"] = $config["data"]->data;
        }
        if(empty($config['columns'])){
            if(count($config['data']) == 0){
                return;
            }
            $config['columns'] = array_keys($config['data'][0]);
        }
        if(isset($config['dataTable'])){
            $config['dataTable'] = array_merge([
                'dom' => 'Bfltips',
                'select'=>true,
            ],$config['dataTable']);
        }
        $tb = new PSSLTable();
        $tb->id = "PSSLTABLE".random_int(100000000,999999999);
        
        print_r("<table class='{$config['css']}' id='{$tb->id}'>");
        $tb->header = TableHead::create($config["columns"],$tb);
        $tb->body = TableBody::create($config["data"],$tb);
        if(isset($config['footer'])){
            $tb->footer = TableFooter::create($config['footer'],$tb);
        }
        print_r("</table>");

        if(isset($config["dataTable"])){
            print_r("<script>");
            print_r("$(document).ready(function() {");
            print_r("$('#{$tb->id}').DataTable(".json_encode($config['dataTable']).");");
            print_r("});");
            print_r("</script>");
        }

    }
}