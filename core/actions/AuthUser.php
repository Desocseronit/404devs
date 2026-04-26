<?php namespace core\actions;

use core\{Response};
use core\{User};

class AuthUser{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();

        $res;
        $userByPassword = User::authByCredentials($body->username,$body->password);
        $user = User::authByCookies();
        if($user){
            $res = new Response(200 , ['user' => $user]);
        }
        elseif($userByPassword){
            $res = new Response(200 , ['user' => $userByPassword]);
        } 
        else {
            $res = new Response(401);
        }
        return $res;    
    }
}