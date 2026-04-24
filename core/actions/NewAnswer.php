<?php namespace core\actions;
use core\{Response};
use core\{Post};
use core\{Database};
use core\{Answer};
use core\{User};

class NewAnswer{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId);
        $post = Post::find($body->postId);
        $imgs;
        if($req->getInfo()->files){
            $imgs = new NewImages();
            $imgs = $imgs->execute($req);
        }
        $answerData = new AnswerData(user: $user , post: $post , text: $body->text , images_ids: $imgs);
        $answer = Answer::create($answerData);
        $response = new Response(201 , ['answer' = $answer]);
        $response->send();
    }
}