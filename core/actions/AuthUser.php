<?php namespace core\actions;

use core\{Response};
use core\{User};

class AuthUser{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();

        $res;

        $user = User::authByCookies();
        if($user){
            $res = new Response(200 , ['user' => $user]);
        }
        elseif() //??? и как какать если у меня запись не возвращает
    }
}