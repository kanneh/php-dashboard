<?php
namespace Kanneh\PhpDashboard\Utils;

use Kanneh\PhpDashboard\PSSLReport;

class PSSLGroup{
    private $config;
    public $data = [];

    public function __construct($config=[])
    {
        $this->config = $config;
    }
    
    function handle(PSSLReport $report){
        $this->data = $report->getData();
        $groups = [];
        $result = [];
        $groupArr = $this->config[1];
        foreach ($this->data as $row) {
            $key = [];
            foreach ($groupArr as $column) {
                $key[] = $row[$column];
            }
            $groupKey = implode('-', $key);
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [];
                $result[$groupKey] = [];
                foreach ($groupArr as $column) {
                    $result[$groupKey][$column] = $row[$column];
                }
            }
            $groups[$groupKey][] = $row;
        }
        // [[
        //     ['sumofquantities','sum','quantity'],
        //     ['averageofquantities','avg','quantity']
        // ],['year','officer']]

        // print_r($groups);

        foreach($this->config[0] as $cfg) {
            list($ncol, $fxnc, $col) = $cfg;
            switch ($fxnc) {
                case 'count':
                    foreach ($groups as $gkey => $group) {
                        // print_r(count($group));
                        // echo '<br><br>';
                        $result[$gkey][$ncol] = count($group);
                    }
                    break;
                case 'sum':
                    foreach ($groups as $gkey => $group) {
                        $result[$gkey][$ncol] = array_sum(array_column($group, $col));
                    }
                    break;
                case 'avg':
                    foreach ($groups as $gkey => $group) {
                        $result[$gkey][$ncol] = array_sum(array_column($group, $col))/count($group);
                    }
                    break;
                case 'min':
                    foreach ($groups as $gkey => $group) {
                        $result[$gkey][$ncol] = min(array_column($group, $col));
                    }
                    break;
                case 'max':
                    foreach ($groups as $gkey => $group) {
                        $result[$gkey][$ncol] = max(array_column($group, $col));
                    }
                    break;
            }
        }
        // print_r($result);
        $this->data = array_values($result);
        // print_r($this->data);
        $report->setData($this->data);
        return $report;
    }
}