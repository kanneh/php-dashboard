<?php
namespace Kanneh\PhpDashboard;

class PDOMYSQL
{
    public $conn;
    public $report;

    public function __construct($dns, $username, $password,?PSSLReport $report=null)
    {
        $this->conn = new \PDO($dns, $username, $password);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->report = $report;
    }

    private function query($sql, $params = [])  {
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt;
    }
    public function fetch($sql, $params = []) {
        $this->report->setData( $this->query($sql, $params)->fetchAll(\PDO::FETCH_ASSOC));
        return $this->report;
    }
    public function fetchAllSomeColumnArr($tb,$columns,$where,$params = []) {
        $sql = "SELECT ";
        for($i = 0; $i < count($columns); $i++){
            $sql .= $columns[$i].",";
        }
        $sql = substr($sql,0,-1);
        $sql.=" FROM $tb";
        if($where){
            $sql.=" WHERE $where";
        }
        return $this->fetch($sql, $params);   
    }
}