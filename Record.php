<?php namespace core;
/**
 * Экземпляр класса Record представляет собой запись из БД
 */
class Record{
    private $_tableName;
    private $_id;
    private $_oldAtributes;
    public $atributes;

    public function __construct($tableName, $id , $atributes){
        $this->_tableName = $tableName;
        $this->_id = $id;
        $this->_oldAtributes = $atributes;
        

        foreach($atributes as $key=>$val) $this->$key = $val;
    }

    public function __set($name , $value){
        if(array_key_exists($name , $this->_oldAtributes)){
            if(!$name != 'id'){
                $this->attributes[$name] = $value;
                $this->$name = &$this->attributes[$name];

                return $value;
            } else return false;
        }else return false;
    }

    public function update(){
        $newValues = [];

        if(!isset($this->_id)) return false;
        else{
            foreach($this->_oldAtributes as $key=>$val) $newValues[$key] = $this->atributes[$key];
            Database::instance()->updateRecord($this->_tableName , $this->_tableName , "WHERE id = $1", [$this->_id]);
        }
    }
}