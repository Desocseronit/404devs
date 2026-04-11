<?php namespace core;

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