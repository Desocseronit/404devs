<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../actions/AuthUser.php');
require_once(__DIR__.'/../actions/RegNewUser.php');
use core\{Application , Response};
use core\actions\{AuthUser , RegNewUser};
class UserController{
    public function loginRender(){
        if(Application::$user){
            \view\View::renderView(['test' => 'succesfull'] , '/test.php');
            $response = new Response(200);
            return;
        }
        \view\View::renderView(['test' => 'loginPage'] , '/login.php');
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

    public function regRender(){
        \view\View::renderView(['test' => 'regPage'] , '/register.php');
    }

    public function regUser(){
        $regUserAction = new RegNewUser();
        $result = $regUserAction->execute();
        $response = null;
        if(!$result){
            \view\View::renderView(['test' => 'regFailed'] , '/failedAuth.php');
            $response = new Response(400);
        }
        else{
            \view\View::renderView(['test' => 'regSucces'] , '/test.php');
            $response = new Response(400);
        }
    }
}