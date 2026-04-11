<?php namespace core;

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