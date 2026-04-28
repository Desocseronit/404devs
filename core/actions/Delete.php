<?php namespace core\actions;
use core\{Database , Post , Answer, Image , Response};
require_once ('DeleteImages.php');
class Delete{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $res = null;
        if(isset($body->postId)){
            $post = Post::find($body->postId->getValue());
            if($body->images->getValue()){
                $deleteImgs = new DeleteImages();
                $deleteImgs = $deleteImgs->execute($body->images->getValue());
            }
            $post->delete();
            $res = new Response(200);
        }
        elseif(isset($body->answerId)){
            $answer = Answer::find($body->answerId->getValue());
            $deleteImgs = new DeleteImages();
            $deleteImgs = $deleteImgs->execute($body->images->getValue());
            $answer->delete();
            $res = new Response(200);
        }
        else{
            $res = new Response(400);
        }
        $res->send();
    }
}