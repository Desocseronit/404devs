<?php namespace view;
use core\{Application};
class View{
    private static $path = __DIR__.'/views';
    private static $globalData = [];
    static public function renderView($data, $path = '/main.php'){
        $resultData = array_merge($data , self::$globalData);
        extract($resultData);
        // if(!Application::$user){
        //     хедер без профиля
        // }
        // else{
        //     хедер с профилем
        // }
        include(self::$path.'/guestHeader.php');
        include(self::$path.$path);
        include(self::$path.'/footer.html');
    }

}