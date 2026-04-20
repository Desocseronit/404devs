<?php namespace core;
/**
 * Экземпляр класса Record представляет собой запись из БД
 * 
 * @update => обновляют данные в БД , записывая новые данные из ассоциативного массива $attributes
 */
class Record{
    private $_tableName;
    private $_id;
    private $_oldAttributes;
    public $attributes;

    public function __construct($tableName, $id , $attributes){
        $this->_tableName = $tableName;
        $this->_id = $id;
        $this->_oldAttributes = $attributes;
        

        foreach($attributes as $key=>$val) $this->$key = $val;
    }

    public function __set($name , $value){
        if(array_key_exists($name , $this->_oldAttributes)){
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
            foreach($this->_oldattributes as $key=>$val) $newValues[$key] = $this->attributes[$key];
            Database::instance()->updateRecord($this->_tableName , $newValues , "id = $1", [$this->_id]);
        }
    }
}