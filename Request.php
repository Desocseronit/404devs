<?php namespace core;

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
            $requestInfo['body'] = json_decode($rawBody, true);
        } 
        elseif (stripos($contentType, 'multipart/form-data' ) !== false|| 
                stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                    $requestInfo['body'] = $_POST;
                } 
        else {
            $requestInfo['body'] = null;
        }

        $this->_requestInfo = new Collection($requestInfo);
    }
}
