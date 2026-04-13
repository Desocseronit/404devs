<?php namespace core;

/**
 * Экземпляр класса Collection представляет собой коллекцию состоящию из экземпляров класса CollectionElement
 * @items => возвращает все элементы коллекции в формате [ключ => значение, .....]
 * @values => возвращает все значения из коллекции
 * @keys =>  возвращает все ключи из коллекции, с возможностью вернуть ключ определенного значения
 * @add => добавляет элемент коллекции
 * @has => возвращает наличие элемента в колекции
 * @stringify => приводит всю коллекцию к строковому типу данных
 */

class Collection{
    private $_items = [];

    public function __construct($items = null){
        $this->_items = $items;
        if($items){
            foreach($items as $key=>$val){
                $this->_items[$key] = new CollectionElement($val);
            }
        }
    }

    public function items(){
        return $this->_items;
    }

    public function keys($reqVal = null , $strict = false){
        if($reqVal !== null){
            return array_keys($this->_items , $reqVal , $strict);
        }
        else return array_keys($this->_items);
    }

    public function values(){
        return array_values($this->_items);
    }

    public function add($val , $key = null){
        $val = new CollectionElement($val);
        if($key === null){
            $this->_items[] = $val;
        }
        else $this->_items[$key] = $val;
    }

    public function has($key) { return isset($this->_items[$key]); }

    public function stringify()  {
        $stringifyArr = [];
        foreach($this->_items as $item){
            $stringifyArr[] = $item->stringify();
        }
        return json_encode($stringifyArr); 
    }
}