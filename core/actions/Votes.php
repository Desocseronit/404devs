<?php namespace core\actions;

use core\{Response};
use core\{Post};
use core\{Answer};
use core\{User};
use core\{Database};

class Votes{
    public function execute ($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId);
        $res;
        if(isset($body->postId)){
            $postId = $body->post_id;
            if(Database::instance()->updateRecord(
                'post',
                ['votes'=>'votes + '.$body->value],
                'id = $1',
                [$postId]
            )){
                $res = new Response(200);
            }
            else{$res = new Response(500);}
        }
        elseif(isset($body->answerId)){
            $answerId = $body->answerId;
            if(Database::instance()->updateRecord(
                'answers',
                ['votes'=>'votes + '.$body->value],
                'id = $1',
                [$answerId]
            )){
                $res = new Response(200);
            }
            else{$res = new Response(500);}
        }
        $res->send();
    }
}
