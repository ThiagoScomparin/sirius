<?php

class Connect
{
    private $host     = "LOCALHOST";
    private $dbName   = "DATABASE";
    private $userName = "USERNAME";
    private $passUser = "PASSWORD";

    private $conn;

    //conecta no banco
    public function __construct(){
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->userName, $this->passUser);
        $this->conn->exec("set names utf8");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    //executa no banco
    public function query($rawQUery, $params = array()){
        try {
            $stmt = $this->conn->prepare($rawQUery);
            $this->setParams($stmt, $params);
            $stmt->execute();
            return $stmt;
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }

    }

    //insert and get last ID
    public function queryAndLastID($rawQUery, $params = array()){
        try {
            $stmt = $this->conn->prepare($rawQUery);
            $this->setParams($stmt, $params);
            $stmt->execute();
            $lastId = $this->conn->lastInsertId();
            return $lastId;
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    //executa no banco e retorna true or false
    public function queryReturn($rawQUery, $params = array()){
        try {
            $stmt = $this->conn->prepare($rawQUery);
            $this->setParams($stmt, $params);
            $res = $stmt->execute();
            return $res;
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    //delete and return affected rows
    public function queryAffectedRows($rawQUery, $params = array()){
        try {
            $stmt = $this->conn->prepare($rawQUery);
            $this->setParams($stmt, $params);
            $stmt->execute();
            $count = $stmt->rowCount();
            return $count;
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    //select na tabela
    public function select($rawQuery, $params = array()){
        try {
            $stmt = $this->query($rawQuery, $params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    //seta os parametros na bind
    private function setParams($statment, $parameters = array() ){
        foreach($parameters as $key => $value){
            $this->setParam($statment, $key, $value);
        }
    }

    //executa a bind
    private function setParam($statment, $key, $value){
        $statment->bindParam($key, $value);
    }

}