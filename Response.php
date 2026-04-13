<?php namespace core;
/**
 * Экземпляр класса Response представляет собой ответ от сервера
 * При создании создает экземпляр класса Collection где хранятся данные по ключам:
 * 1) code => http код ответа
 * 2) body => тело ответа
 * 
 * @send =>  отправляет ответ от сервера к пользователю
 */

class Response{
    private $_code;
    private $_body = [];

    public function __construct($code , $body = []){
        $this->_code = $code;
        $this->_body = new Collection($body);
    }

    public function send(){
        http_response_code($this->_code);
        echo json_encode([
            'code' => $this->_code,
            'body' => $this->_body->stringify()  
        ]);
    }
}
