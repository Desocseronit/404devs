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
        if($body instanceof Collection) $this->_body = $body;
        else $this->_body = new Collection($body);
    }

    public function send(){
        http_response_code($this->_code);
        if($this->_body->items()){
            echo json_encode([
                'body' => $this->_body->stringify()  
            ]);
        }
    }

    public static function redirect($url, $statusCode = 302) {
        header('Location: ' . $url, true, $statusCode);
        $resp = new self($statusCode);
        exit;
    }
}
