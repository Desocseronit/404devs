<?php namespace core\actions;

use core\{Response,Post,User,Database};

class Views{
    public function execute ($req){ 
        $body = $req->getInfo()->body->getValue();
        $res = null;
        if($body->postId->getValue()){
            if(Database::instance()->incrementField('posts', 'views' , (int)$body->value->getValue(), 'id = $1', [$body->postId->getValue()])){
                $res = new Response(200, ["post" => Post::find($body->postId->getValue())]);
            }
            else{$res = new Response(500);}
        } else $res = new Response(400);
        $res->send();
    }
}
