<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../actions/AuthUser.php');
require_once(__DIR__.'/../actions/RegNewUser.php');
use core\{Application , Response , User};
use core\actions\{AuthUser , RegNewUser};
class UserController{
    public function loginRender(){
        if(Application::$user){
            Response::redirect('/all-posts');
        }
        \view\View::renderView(['test' => 'loginPage'] , '/login.php');
    }

    public function authUser(){
        $authUserAction = new AuthUser();
        $result = $authUserAction->execute();
        $response = null;
        if(!$result){
            $response = new Response(401, ['error' => 'Login or password is incorrect']);
            $response -> send();
        }
        else{
            Response::redirect('/all-posts');
        }
    }

    public function regRender(){
        \view\View::renderView(['test' => 'regPage'] , '/register.php');
    }

    public function regUser(){
        $regUserAction = new RegNewUser();
        $result = $regUserAction->execute();
        $response = null;
        if(!$result){
            $response = new Response(403);
        }
        else{
            Response::redirect('/all-posts');
        }
        $response -> send();
    }

    public function profileRender($params){
        if($params['id'] == null && !Application::$user){
            Response::redirect('/login');
            return;
        }
        $userId = $params['id'];
        $user = null;
        if(!Application::$user || Application::$user->id != $userId) $user = User::find($userId);
        else $user = Application::$user;
        \view\View::renderView(["userData" => $user->stringify()] , '/profileView.php');
        $response = new Response(200);
        $response -> send();
    }
}