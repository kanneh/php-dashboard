<?php
namespace Kanneh\PhpDashboard\Wiget;

class PSSLBaseWiget{
    public function __construct(){
        // echo "PSSLBaseWiget";
    }

    public static function create(array $params = []){
        print_r("Base Wiget");
    }
}