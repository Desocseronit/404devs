<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../actions/AuthUser.php');
use core\{Application , Response};
use core\actions\{AuthUser};
class UserController{
    public function loginRender(){
        \view\View::renderView(['test' => 'loginPag'] , '/login.php');
    }

    public function authUser(){
        $authUserAction = new AuthUser();
        $result = $authUserAction->execute();
        $response = null;
        if(!$result){
            \view\View::renderView(['test' => 'loginFailed'] , '/failedAuth.php');
            $response = new Response(400);
        }
        else{
            \view\View::renderView(['test' => 'succesfull'] , '/test.php');
            $response = new Response(200);
        }
        $response -> send();
    }
}