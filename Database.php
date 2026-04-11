<?php namespace core;
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

    public function query($query){
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
        }
    }
}

