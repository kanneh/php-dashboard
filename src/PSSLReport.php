<?php
namespace Kanneh\PhpDashboard;

class PSSLReport
{
    public $db;
    public $dataSources = [];
    private $data;

    private $dataStores = [];

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function __construct()
    {
        $config = $this->settings();
        foreach ($config['dataSources'] as $key => $value) {
            $this->dataSources[$key]['conn'] = new PDOMYSQL($value['connectionString'], $value['username'], $value['password'],$this);
        }
        $this->setUp();
    }
    public function setUp(){

    }

    public function settings(){
        return [];
    }

    public function pipe(object $instance)
    {
        if (is_callable([$instance, 'handle'])) {
            $instance->handle($this);
        }
        return $this;
    }

    public function src($name)
    {
        return $this->dataSources[$name]['conn'];
    }

    public function addStore($name, $store)
    {
        $this->dataStores[$name] = $store;
    }
    public function dataStore($name){
        return $this->dataStores[$name];
    }

    public function resetToDataStore($name){
        $this->data = $this->dataStore($name)->get();
        return $this;
    }
}
