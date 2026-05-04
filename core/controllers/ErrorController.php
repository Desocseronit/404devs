<?php namespace controllers;
require_once(__DIR__.'/../View.php');
use core\{Application , Response};

class ErrorController{
    public function render404(){
        \view\View::renderView(path: '/errors/404err.php');
        $response = new Response(404);
        $response->send();
        return;
    }
}