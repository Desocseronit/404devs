<?php namespace core;
/**
 * Экземпляр класса Record представляет собой запись из БД
 * 
 * @update => обновляют данные в БД , записывая новые данные из ассоциативного массива $atributes
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
                $this->atributes[$name] = $value;
                $this->$name = &$this->atributes[$name];

                return $value;
            } else return false;
        }else return false;
    }

    public function update(){
        $newValues = [];

        if(!isset($this->_id)) return false;
        else{
            foreach($this->_oldAtributes as $key=>$val) $newValues[$key] = $this->atributes[$key];
            Database::instance()->updateRecord($this->_tableName , $newValues , "id = $1", [$this->_id]);
        }
    }
}