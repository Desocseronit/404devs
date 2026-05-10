<?php namespace core;
class Application{
    static $CONFIG;
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

    static function initConfig(){
        self::$CONFIG = require_once('config.php');
    }

    static public function executePath(){
        self::authUser();
        $request = self::$request;
        $requestType = $request->getInfo()->type->getValue();
        $requestURI = $request->getInfo()->uri->getValue();
        $allPathes = include 'routeConfig.php';
        if($requestURI->{0}->getValue() == 'about'){
            require_once('View.php');
            \view\View::renderView(path : '/about.php');
        }
        else{
            $targetPath = null;
            foreach($allPathes as $key => $path){
                if(!is_numeric($key)) {
                    continue;
                }
                if($path['type'] == $requestType && $path['uri'] == $requestURI->{0}->getValue()){
                    $targetPath = $path;
                }
            }
            if($targetPath == null){
                require_once('controllers/ErrorController.php');
                foreach($allPathes['errors'] as $path){
                    if($path['error'] == 404){
                        $targetPath = $path;
                    }
                }
                $errorController = new \controllers\ErrorController();
                $errorController->render404();
                return;
            }
            require_once('controllers/'.$targetPath['controller'].'.php');
            if(!method_exists('controllers\\'.$targetPath['controller'], $targetPath['view'])){
                require_once('controllers/ErrorController.php');
                foreach($allPathes['errors'] as $path){
                    if($path['error'] == 404){
                        $targetPath = $path;
                    }
                }
                $errorController = new \controllers\ErrorController();
                $errorController->render404();
                return;
            }
            require_once('controllers/'.$targetPath['controller'].'.php');
            $controllerName = 'controllers\\'.$targetPath['controller'];
            $controller = new $controllerName();
            $controller->{$targetPath['view']}(Application::checkDependencies($targetPath));
        }
        
    }

    static private function checkDependencies($targetPath){
        $requestParams = null;
        if(isset(Application::$request->getInfo()->params)) $requestParams = Application::$request->getInfo()->params->getValue();
        $params = [];
        foreach($targetPath['dependencies'] as $key => $val){
            if(!isset($requestParams->$key) || !$requestParams->$key->getValue()){
                $params[$key] = $val[0];
            }
            else{
                if((in_array($requestParams->$key->getValue() , $val[1]) || empty($val[1])) && (!in_array($requestParams->$key->getValue() , $val[2]) || empty($val[2]))) $params[$key] = $requestParams->$key->getValue();
                else $params[$key] = $val[0];
            }
        }
        return $params;
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
Application::initConfig();
Application::requireFiles(__DIR__ .'/modules/');
Application::createRequest();
Database::instance(Application::$CONFIG['dbConn']);