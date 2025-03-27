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
            $config['columns'] = array_keys($config['data'][0]);
        }
        $tb = new PSSLTable();
        $tb->id = random_bytes(9);
        
        print_r("<table class='{$config['css']}'>");
        $tb->header = TableHead::create($config["columns"],$tb);
        $tb->body = TableBody::create($config["data"],$tb);
        if(isset($config['footer'])){
            $tb->footer = TableFooter::create($config['footer'],$tb);
        }
        print_r("</table>");

    }
}