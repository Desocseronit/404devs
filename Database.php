<?php namespace core;

/**
 * Экземпляр класса Database существует для взаимодействия с БД
 * Класс осущесвляет возможможность открыть только одно подключение к БД
 * @query => отвечает за выполнение SQL запросов к БД
 */

class Database{
    private static $_instance = null;
    private $_connection;

    private function __construct($connect_str) {
        $this->_connection = pg_connect($connect_str);
        if (!$this->_connection) {
            die('Connection error: ' . pg_last_error());
        }
    }

    public static function instance($connect_str = null) {
        if (self::$_instance === null) {
            if ($connect_str === null) {
                return null;
            }
            self::$_instance = new self($connect_str);
        }
        return self::$_instance;
    }

    public function connection(){
        return $this->_connection;
    }

    public function getRecord($tablename , $attributes = '*' , $condition = [] , $limit = null){
        if(is_array($attributes)){
            $attributes = implode(' , ', $attributes);
        }
        $condition = conditionConstructor($condition);
        $query = "SELECT $attributes FROM $tablename $condition $limit";
        return $this->query($query);
    }

    private function query($query){
        $query = trim($query);
        if(str_starts_with(strtolower($query), "select")){
            $records = pg_query($this->connection(), $query);
            $res = [];
            while ($record = pg_fetch_assoc($records)){
                $res[] = $record;
            }
            $res = new Collection($res);
            return $res->stringify();
        }
        else{
            pg_query($this->connection(), $query);
            return true;
        }
    }
}

