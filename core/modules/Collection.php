<?php namespace core;

/**
 * Экземпляр класса Collection представляет собой коллекцию состоящию из экземпляров класса CollectionElement
 * Предоставляет возможность обратиться к элементу как к свойству объекта
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
        if($items){
            foreach($items as $key=>$val){
                $this->_items[$key] = new CollectionElement($val);
                $this->$key = &$this->_items[$key];
            }
        }
    }

    public function __set($name , $val){
        if (!($value instanceof CollectionElement)) {
            $value = new CollectionElement($value);
        }
        $this->_items[$name] = $value;
        $this->$name = &$this->_items[$name];
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
        else {
            $this->_items[$key] = $val;
            $this->$key = &$this->_items[$key];
        }
    }

    public function has($key) { return isset($this->_items[$key]); }

    public function stringify()  {
        return json_encode(array_map(fn($item) => $item->getValue(), $this->_items)); 
    }
}