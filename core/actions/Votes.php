<?php namespace core\actions;

use core\{Response};
use core\{Post};
use core\{Answer};
use core\{User};
use core\{Database};

class Votes{
    public function execute ($req){ 
        $body = $req->getInfo()->body->getValue();
        // $user = User::find($body->userId->getValue()); придумать как сохранять лайки юзеров (1 таблица (id user_id ?post_id ?answer_id) vs 2 таблицы (1) id , user_id, post_id   2) id, user_id , answer_id))
        $res = null;
        if(isset($body->postId)){
            if(Database::instance()->incrementField('posts', 'votes' , (int)$body->value->getValue(), 'id = $1', [$body->postId->getValue()])){
                $res = new Response(200);
            }
            else{$res = new Response(500);}
        }
        elseif(isset($body->answerId)){
            if(Database::instance()->incrementField('answers', 'votes' , (int)$body->value->getValue(), 'id = $1', [$body->answerId->getValue()])){
                $res = new Response(200);
            }
            else{$res = new Response(500);}
        }
        $res->send();
    }
}
