<?php namespace core;

class Application{
    static $user;
    static $request;
    // static public function runAction(){
        
    //     $req = new Request();
    //     $actionName = $req->getInfo()->params->getValue()->action->getvalue();
    //     require_once('.\\actions\\'.$actionName.'.php');
    //     $className = "core\\actions\\" . $actionName;

    //     if (class_exists($className)) {
    //         $handler = new $className();
    //         $handler->execute($req);
    //     } else {
    //         $res = new Response(404 , ['message' => 'Not existed']);
    //         $res->send();
    //     }
    // }
    static public function executePath(){
        self::authUser();
        $request = self::$request;
        $requestType = $request->getInfo()->type->getValue();
        $requestURI = $request->getInfo()->uri->getValue();
        $requestParams = null;
        if(isset($request->getInfo()->params)) $requestParams = $request->getInfo()->params->getValue();
        $allPathes = include 'routeConfig.php';
        if($requestURI->{0}->getValue() == 'main'){
            require_once('controllers/PostsController.php');
            $controllerName = 'controllers\\PostsController';
            $controller = new $controllerName();
            $params = [];
            $targetPath = $allPathes[0];
            foreach($targetPath['dependencies'] as $key => $val){
                if(!isset($requestParams->$key) || !$requestParams->$key->getValue()){
                    $params[$key] = $val;
                }
                else{
                    $params[$key] = $requestParams->$key->getValue();
                }
            }
            $controller->allPosts($params);
        }
        elseif($requestURI->{0}->getValue() == 'about'){
            echo 'its the about page';
        }
        else{
            $targetPath = null;
            foreach($allPathes as $path){
                if($path['type'] == $requestType && $path['uri'] == $requestURI->{0}->getValue()){
                    $targetPath = $path;
                }
            }
            require_once('controllers/'.$targetPath['controller'].'.php');
            $controllerName = 'controllers\\'.$targetPath['controller'];
            $controller = new $controllerName();
            $params = [];
            foreach($targetPath['dependencies'] as $key => $val){
                if(!isset($requestParams->$key) || !$requestParams->$key->getValue()){
                    $params[$key] = $val;
                }
                else{
                    $params[$key] = $requestParams->$key->getValue();
                }
            }
            $controller->{$targetPath['view']}($params);
        }
        
    }

    static public function requireFiles($dir = '') {
        foreach(scandir($dir) as $path) {
            if ($path !== '.' && $path !== '..') {
                if (is_dir("$dir/$path")) self::requireFiles("$dir/$path");
                else require_once("$dir/$path");
            }
        }
    }

    static public function authUser(){
        $userInctance = User::authByCookies();
        if($userInctance) Application::$user = $userInctance;
        else return false;
    }

    static public function createRequest(){
        if(!self::$request){
            self::$request = new Request();
        }
        return self::$request;
    }
}

Application::requireFiles(__DIR__ .'/modules/');
Application::createRequest();
Database::instance('host = 26.152.118.24 port = 5432 dbname = 404devs user = postgres password = 1');

