<?php namespace core;

class Application{
    static public function runAction(){
        self::requireFiles(__DIR__ .'/modules/');

        $actionName = new Request();
        $actionName = $actionName->getInfo()->params->getValue()->action->getvalue();
        require_once('.\\actions\\'.$actionName.'.php');
        $className = "core\\actions\\" . $actionName;

        if (class_exists($className)) {
            $handler = new $className();
            $handler->execute();
        } else {
            $req = new Response(404 , ['message' => 'Not existed']);
            $req->send();
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
}

Application::runAction();

