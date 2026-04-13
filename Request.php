<?php namespace core;

/**
 * Экземпляр класса Request представляет собой запрос к серверу
 * При создании создает экземпляр класса Collection где хранятся данные по ключам:
 * 1) type => тип запроса (POST , GET .....)
 * 2) head => заголовки запроса
 * 3) body => тело запроса
 */

class Request
{
    private $_requestInfo;

    public function __construct()
    {
        $requestInfo['type'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestInfo['head'] = getallheaders();

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $rawBody = file_get_contents('php://input');
            $requestInfo['body'] = new Collection(json_decode($rawBody, true));
        } 
        elseif (stripos($contentType, 'multipart/form-data' ) !== false|| 
                stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                    $requestInfo['body'] = new Collection($_POST);
                } 
        else {
            $requestInfo['body'] = null;
        }

        $this->_requestInfo = new Collection($requestInfo);
    }
}
