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

    public function render401(){
        \view\View::renderView(path: '/errors/401err.php');
        $response = new Response(401);
        $response->send();
        return;
    }
}