<?php namespace core\actions;

use core\{Response,Post,Answer,User,Database};


class Votes{
    public function execute ($req){ 
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId->getValue());
        $res = null;
        if(isset($body->postId)){
            if(Database::instance()->incrementField('posts', 'votes' , (int)$body->value->getValue(), 'id = $1', [$body->postId->getValue()])){
                if(Database::instance()->selectRecord('voted_posts', '*' , [['user_id', '=' , $user->id],['post_id','=',$body->postId->getValue()]])->items()){
                    $res = new Response(400, ["message" => 'already voted']);
                }
                else{
                    Database::instance()->insertRecord('voted_posts', ['user_id' => $user->id , 'post_id' => $body->postId->getValue()]);
                    $res = new Response(200);
                }
                
            }
            else{$res = new Response(500);}
        }
        elseif(isset($body->answerId)){
            if(Database::instance()->incrementField('answers', 'votes' , (int)$body->value->getValue(), 'id = $1', [$body->answerId->getValue()])){
                if(Database::instance()->selectRecord('voted_answers', '*' , [['user_id', '=' , $user->id],['answer_id','=',$body->answerId->getValue()]])->items()){
                    $res = new Response(400, ["message" => 'already voted']);
                }
                else{
                    Database::instance()->insertRecord('voted_answers', ['user_id' => $user->id , 'answer_id' => $body->answerId->getValue()]);
                    $res = new Response(200);
                }
            }
            else{$res = new Response(500);}
        }
        $res->send();
    }
}
