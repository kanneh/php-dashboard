<?php
namespace Kanneh\PhpDashboard\Wiget;

class PSSLCard{

    public static function create(array $config){
        if(isset($config["dataStore"])){
            
            if(isset($config['column'])){
                $dt = $config["dataStore"]->get($config['column']);
            }else{
                $dt = $config["dataStore"]->get()[0];
                $dt = array_values($dt);
            }

            if(isset($config['fxn'])){
                switch($config['fxn']){
                    case 'first':
                        $config['value']=$dt[0];
                        break;
                    case 'sum':
                        $config['value']=array_sum($dt);
                        break;
                    case 'avg':
                        $config['value']=array_sum($dt)/count($dt);
                        break;
                    case 'countdistinct':
                        $dt = array_filter($dt,fn($x) => $x != ''&& $x != null);
                        $config['value']=count(array_unique($dt));
                        break;
                    default:
                        $dt = array_filter($dt,fn($x) => $x != ''&& $x != null);
                        $config['value']=count($dt);
                        break;
                }
            }else{
                // print_r($dt);
                if(!empty($dt)){
                    $i = 0;
                    foreach($dt as $key => $value){
                        if($i == 0){
                            $config['value'] = $value;
                        }
                        $i++;
                    }
                }else{
                    $config['value'] = "";
                }
            }
        }
        $config = array_merge([
            'css'=>"text-center text-dark",
            'cssCard'=>'shadow rounded'
        ],$config);

        print_r("<div class='{$config['cssCard']} m-2' style='min-width:150px;'>");
        if(isset($config["title"])){
            if(is_string($config["title"])){
                $config['title'] = [
                    'text'=> $config['title'],
                ];
                
            }
            $tcf = array_merge([
                'text'=>'',
                'css'=>'',
                'cssStyle'=>''
            ],$config['title']);
            print_r("<div class='{$tcf["css"]} p-2' style='{$tcf["cssStyle"]}'>{$tcf['text']}</div>");
            print_r("<hr style='
                height: 2px;
                background-color: black;
                margin: 0;
            '>");
        }
        print_r("<div class='{$config['css']} p-4'>".$config['value']."</div>");
        if(isset($config["caption"])){
            if(is_string($config["caption"])){
                $config['caption'] = [
                    'text'=> $config['caption'],
                ];
                
            }
            $tcf = array_merge([
                'text'=>'',
                'css'=>'',
                'cssStyle'=>''
            ],$config['caption']);
            print_r("<div class='{$tcf["css"]}' style='{$tcf["cssStyle"]}'>{$tcf['text']}</div>");
        }
        print_r("</div>");
    }
}