<?php namespace core;

class User{
    private $record;

    public function __construct($record){
        $this->record = $record;

        foreach($record->attributes as $key => $val) { 
            $this->$key = &$record->$key;
        }
    }

    public function __set($name , $value){
        $this->$name = &$this->attributes[$name];
        return $value;
    }
}