<?php namespace core;

/**
 * Экземпляр класса CollectionElement представляет атомарное значение находящиеся в экземпляре класса Collection
 * @setValue => устанавливает значение у экемпляра класса
 * @getvalue => получает значение
 * @stringify => приводит значение экземпляра класса к строчному типу данных
 */

class CollectionElement{
    private $_value = null;

    public function __construct($val = null){
        $this->_value = $val;
    }

    public function setValue($value){
        $this->_value = $value;
    }

    public function getValue(){
        return $this->_value;
    }

    public function stringify(){
        return json_encode($this->getValue());
    }
}