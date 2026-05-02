<?php namespace core\actions;
require_once('NewImages.php');

use core\{Response,Post,Database,Answer,AnswerData,User};

class NewAnswer{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId->getValue());
        $post = Post::find($body->postId->getValue());
        $imgs = [];
        if($req->getInfo()->files->getValue()){
            $imgs = new NewImages();
            $imgs = $imgs->execute($req);
        }
        $answerData = new AnswerData(user: $user , post: $post , text: $body->text->getValue() , images_ids: $imgs);
        $answer = Answer::create($answerData);
        $response = new Response(201 , ['answer' => $answer]);
        $response->send();
    }
}