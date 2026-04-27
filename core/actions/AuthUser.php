<?php namespace core\actions;

use core\{Response,User};

class AuthUser{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();

        $res;
        $userByPassword = User::find($body->UserId->getValue());
        $user = User::find($body->UserId->getValue());
        if($user){
            $user->authByCokies()
            $res = new Response(200 , ['user' => $user]);
        }
        elseif($userByPassword){
            $userByPassword->authByCredentials();
            $res = new Response(200 , ['user' => $userByPassword]);
        } 
        else {
            $res = new Response(401);
        }
        return $res;    
    }
}