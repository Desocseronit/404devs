<?php namespace core\actions;
use core\{Database , Post , Answers, Image};

class Delete{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $res = null;
        if(isset($body->postId)){
            $post = Post::find($body->postId->getValue());
            // Database::instance()->deleteRecord('post_images' , 'post_id = $1' , [$post->id]); а надо ли?
            $deleteImgs = new DeleteImages()
            $deleteImgs = $deleteImgs->execute($body->images->getValue());
            $post->delete();
            $res = new Response(200);
        }
        elseif(isset($body->answerId)){
            $answer = Answer::find($body->answerId->getValue());
            // Database::instance()->deleteRecord('answer_images' , 'answer_id = $1' , [$answer->id]); а надо ли?
            $deleteImgs = new DeleteImages()
            $deleteImgs = $deleteImgs->execute($body->images->getValue());
            $answer->delete();
            $res = new Response(200);
        }
        else{
            $res = new Response(400);
        }
        $res->send()
    }
}