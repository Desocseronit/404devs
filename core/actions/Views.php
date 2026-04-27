<?php namespace core\actions;

use core\{Response,Post,User,Database};

class Views{
    public function execute ($req){ 
        $body = $req->getInfo()->body->getValue();
        // $user = User::find($body->userId->getValue()); придумать как сохранять лайки юзеров (1 таблица (id user_id ?post_id ?answer_id) vs 2 таблицы (1) id , user_id, post_id   2) id, user_id , answer_id))
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
