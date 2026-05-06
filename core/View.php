<?php namespace view;
use core\{Application};
class View{
    private static $globalPath = __DIR__.'/views';
    private static $globalData = [];
    static public function renderView($data = [], $path = '/main.php'){
        $resultData = array_merge($data , self::$globalData);
        extract($resultData);
        if(!Application::$user){
            include(self::$globalPath.'/guestHeader.php');
        }
        else{
            include(self::$globalPath.'/header.php');
        }
        include(self::$globalPath.$path);
        include(self::$globalPath.'/footer.php');
    }

}