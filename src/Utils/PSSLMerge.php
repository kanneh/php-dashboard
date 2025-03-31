<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLDataStore;
use Kanneh\PhpDashboard\PSSLReport;

class PSSLMerge{
    public $data = [];
    public function __construct(array $config){
        $store1 = $config['dataStore1'];
        if (!$store1 instanceof PSSLDataStore) {
            throw new \InvalidArgumentException('dataStore1 must be an instance of PSSLDataStore');
        }
        $store2 = $config['dataStore2'];
        if (!$store2 instanceof PSSLDataStore) {
            throw new \InvalidArgumentException('dataStore2 must be an instance of PSSLDataStore');
        }
        $joinConditions = $config['condition'];
        $src2name = $store2->name;
        $data1 = $store1->data;
        $data2 = $store2->data;

        // print_r($data1);
        // echo "<br>";
        // print_r($data2);
        // echo "<br><br>";

        if(isset($config['type']) && $config['type'] === "inner"){
            // print_r("Inner");
            // echo "<br><br>";
            $this->data = [];
            foreach($data1 as $row){
                $found = false;
                foreach($data2 as $row2){
                    // echo "Testing Resultant: ";

                    $resultant = $this->test($joinConditions[0],$row,$row2);
                    // echo "done<br><br>";
                    $nextjoin = count($joinConditions)>1?$joinConditions[1]:"";
                    if($resultant && ($nextjoin == "" || $nextjoin == "OR")){
                        $this->data[] = $this->buildRow($row,$row2,$src2name);
                        break;
                    }
                    $nextcond = "";
                    $i=2;
                    $cancontinue = count($joinConditions) > $i && $nextjoin != "";
                    while($cancontinue){
                        $nextcond = $this->test($joinConditions[$i],$row,$row2);
                        if($nextjoin == "OR"){
                            if($nextcond){
                                $this->data[] = $this->buildRow($row,$row2,$src2name);
                                $found = true;
                                break;
                            }
                            $resultant = $nextcond;
                        }else{
                            $resultant == $resultant && $nextcond;
                        }
                        $i++;
                        $cancontinue = count($joinConditions)> $i+1;
                        if($cancontinue){
                            $nextjoin = $joinConditions[$i];
                            if($nextjoin == "OR" && $resultant){
                                $this->data[] = $this->buildRow($row,$row2,$src2name);
                                $found = true;
                                break;
                            }
                            $i++;
                        }
                    }
                    if($found){
                        break;
                    }
                }
            }

        }elseif(isset($config['type']) && $config['type'] === "right"){
            
            $this->data = array_map(function($row) use ($data1,$joinConditions,$src2name){
                foreach($data1 as $row2){
                    $resultant = $this->test($joinConditions[0],$row,$row2,true);
                    $nextjoin = count($joinConditions)>1?$joinConditions[1]:"";
                    if($resultant && ($nextjoin == "" || $nextjoin == "OR")){
                        return $this->buildRow($row2,$row,$src2name);
                    }elseif($nextjoin == ""){
                        $row2 = $data1[0];
                        foreach ($row2 as $key => $value) {
                            $row2[$key] = null;
                        }
                        return $this->buildRow($row2,$row,$src2name);
                    }
                    $nextcond = "";
                    $i=2;
                    $cancontinue = count($joinConditions) > $i;
                    while($cancontinue){
                        $nextcond = $this->test($joinConditions[$i],$row,$row2,true);
                        if($nextjoin == "OR"){
                            if($nextcond){
                                return $this->buildRow($row2,$row,$src2name);
                            }
                            $resultant = $nextcond;
                        }else{
                            $resultant == $resultant && $nextcond;
                        }
                        $i++;
                        $cancontinue = count($joinConditions)> $i+1;
                        if($cancontinue){
                            $nextjoin = $joinConditions[$i];
                            if($nextjoin == "OR" && $resultant){
                                return $this->buildRow($row2,$row,$src2name);
                            }
                            $i++;
                        }
                    }

                }
                $row2 = $data1[0];
                foreach ($row2 as $key => $value) {
                    $row2[$key] = null;
                }
                return $this->buildRow($row2,$row,$src2name);
            },$data2);

        }else{
            foreach($data1 as $row){
                $found = false;
                foreach($data2 as $row2){
                    // echo "Testing Resultant: ";

                    $resultant = $this->test($joinConditions[0],$row,$row2);
                    // echo "done<br><br>";
                    $nextjoin = count($joinConditions)>1?$joinConditions[1]:"";
                    if($resultant && ($nextjoin == "" || $nextjoin == "OR")){
                        $this->data[] = $this->buildRow($row,$row2,$src2name);
                        $found = true;
                        break;
                    }
                    $nextcond = "";
                    $i=2;
                    $cancontinue = count($joinConditions) > $i && $nextjoin != "";
                    while($cancontinue){
                        $nextcond = $this->test($joinConditions[$i],$row,$row2);
                        if($nextjoin == "OR"){
                            if($nextcond){
                                $this->data[] = $this->buildRow($row,$row2,$src2name);
                                $found = true;
                                break;
                            }
                            $resultant = $nextcond;
                        }else{
                            $resultant == $resultant && $nextcond;
                        }
                        $i++;
                        $cancontinue = count($joinConditions)> $i+1;
                        if($cancontinue){
                            $nextjoin = $joinConditions[$i];
                            if($nextjoin == "OR" && $resultant){
                                $this->data[] = $this->buildRow($row,$row2,$src2name);
                                $found = true;
                                break;
                            }
                            $i++;
                        }
                    }
                    if($found){
                        break;
                    }
                }
                if(! $found){
                    $row2 = $data2[0];
                    foreach ($row2 as $key => $value) {
                        $row2[$key] = null;
                    }
                    $this->data[] = $this->buildRow($row2,$row2,$src2name);
                }
            }
            // $this->data = array_map(function($row) use ($data2,$joinConditions,$src2name){
            //     foreach($data2 as $row2){
            //         $resultant = $this->test($joinConditions[0],$row,$row2);
            //         $nextjoin = count($joinConditions)>1?$joinConditions[1]:"";
            //         if($resultant && ($nextjoin == "" || $nextjoin == "OR")){
            //             return $this->buildRow($row,$row2,$src2name);
            //         }elseif($nextjoin == ""){
            //             $row2 = $data2[0];
            //             foreach ($row2 as $key => $value) {
            //                 $row2[$key] = null;
            //             }
            //             return $this->buildRow($row,$row2,$src2name);
            //         }
            //         $nextcond = "";
            //         $i=2;
            //         $cancontinue = count($joinConditions) > $i;
            //         while($cancontinue){
            //             $nextcond = $this->test($joinConditions[$i],$row,$row2);
            //             if($nextjoin == "OR"){
            //                 if($nextcond){
            //                     return $this->buildRow($row,$row2,$src2name);
            //                 }
            //                 $resultant = $nextcond;
            //             }else{
            //                 $resultant == $resultant && $nextcond;
            //             }
            //             $i++;
            //             $cancontinue = count($joinConditions)> $i+1;
            //             if($cancontinue){
            //                 $nextjoin = $joinConditions[$i];
            //                 if($nextjoin == "OR" && $resultant){
            //                     return $this->buildRow($row,$row2,$src2name);
            //                 }
            //                 $i++;
            //             }
            //         }

            //     }
            //     $row2 = $data2[0];
            //     foreach ($row2 as $key => $value) {
            //         $row2[$key] = null;
            //     }
            //     return $this->buildRow($row,$row2,$src2name);
            // },$data1);
        }
    }
    private function test($criteria,$row1,$row2,$right=false){
        // echo "Test Called: <br>";
        $result = false;
        if(is_callable($criteria)){
            $result = $criteria($row1,$row2);
        }else{
            if($right){
                $rcd1 = $row2[$criteria[0]];
                $rcd2 = $row1[$criteria[1]];
            }else{
                $rcd1 = $row1[$criteria[0]];
                $rcd2 = $row2[$criteria[1]];
            }
            $oper = $this->getOper($criteria[2]);
            // echo "rcd1: $rcd1, rcd2: $rcd2, oper: $oper exp: $rcd1 $oper $rcd2";
            $result = eval("return '$rcd1' $oper '$rcd2';");
        }
        // echo " Result: ".strval($result?"True":"false")."<br>";
        // echo "Test Done<br>";
        return $result;
    }

    private function getOper($val){
        switch($val){
            case "=":
                return "==";
            default:
                return $val;
        }
    }

    private function buildRow($row,$row2, $src): array{
        // echo "Row1: ";
        // print_r($row);
        // echo "<br>Row2: ";
        // print_r($row2);
        // echo "<br><br>";
        foreach($row2 as $key=>$value){
            $row[$src.".".$key] = $value;
        }
        return $row;
    }

    public function handle(PSSLReport $report){
        $report->setData($this->data);
        return $report;
    }
}