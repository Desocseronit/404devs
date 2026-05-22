<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../actions/AuthUser.php');
require_once(__DIR__.'/../actions/RegNewUser.php');
require_once(__DIR__.'/../actions/ModifyUser.php');
use core\{Application , Response , User , Post};
use core\actions\{AuthUser , RegNewUser, ModifyUser};
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
            $response = new Response(401);
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
            $response = new Response(201);
        }
        $response -> send();
        return;
    }

    public function profileRender($params){
        if(($params['id'] == null && !Application::$user) || !User::find($params['id']) ){
            Response::redirect('/login');
            return;
        }
        $userId = $params['id'];
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $category = $params['category'];
        $level = $params['level'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $user = null;
        if(!Application::$user || Application::$user->id != $userId) $user = User::find($userId);
        else $user = Application::$user;
        $posts = Post::paginate($page , Application::$CONFIG['publicationsPerPage'] , $filterBy , $side , $idSide , $category , $level , $user->id);
        // \view\View::renderView(["userData" => $user->stringify() , "posts" => $posts] , '/profile.php');
        \view\View::renderView(['data'=>["userData" => !Application::$user || $userId != Application::$user->id ? $user->stringify(true): $user->stringify() , "posts" => $posts]] , '/profile.php');
        $response = new Response(200);
        $response -> send();
    }

    public function logout(){
        if(Application::$user) Application::$user->logOut();
        Response::redirect('/all-posts', 303);
    }

    public function deleteUser(){
        Application::$user->delete();
        $res = new Response(200);
        $res->send();
        return;
    }

    public function editUser(){
        $actionInstance = new ModifyUser();
        $res = $actionInstance->execute();
        $resp;
        if($res) $resp = new Response(200);
        else $resp = new Response(400);
        $resp->send();
        return;
    }

    public function findUser($params){
        $name = $params['name'];
        $users = User::findByShowName($name);
        if($name != '') $users[] = User::findByLogin($name);
        $notFilteredData = [];

        foreach ($users as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    if ($subItem instanceof User) {
                        $notFilteredData[$subItem->id] = $subItem;
                    }
                }
            } elseif ($item instanceof User) {
                $notFilteredData[$item->id] = $item;
            }
        }
        $data = [];
        foreach(array_values($notFilteredData) as $user){
            $data[] = $user->stringify(true);
        }

        \view\View::renderView(['test' => $data] , '/test.php');
        return;
    }
}