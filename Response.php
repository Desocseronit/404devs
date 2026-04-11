<?php namespace core;
class Response{
    private $_code;
    private $_data = [];

    public function __construct($code , $data = []){
        $this->_code = $code;
        $this->_data = new Collection($data);
    }

    public function send(){
        http_response_code($this->_code);
        echo json_encode([
            'code' => $this->_code,
            'data' => $this->_data->stringify()  
        ]);
    }
}
