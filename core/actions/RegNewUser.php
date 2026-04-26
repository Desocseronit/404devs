<?php namespace core\actions;

use core\{Response};
use core\{User};
use core\{Database};

class RegNewUser{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();

        $user = User::reg($body->username, $body->password , $body->email);
        if($user){
            $res = new Response(201 , ['user' => $user, ]);
        }
        else{
            $res = new Response(400);
        }
        $res->send();
    }
}