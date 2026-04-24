<?php namespace core\actions;

use core\{Response};
use core\{User};
use core\{Database};

class RegNewUser($req){
    $body = $req->getInfo()->body->getValue();

    $user = User::reg($body->username, $body->password , $body->email);

    $res =  new Response(201);
    $res->send();
}