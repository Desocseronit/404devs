<?php namespace core\actions;
require_once('NewImages.php');

use core\{Application , Response,Post,Database,Answer,AnswerData,User};

class NewAnswer{
    public function execute(){
        $req = Application::$request->getInfo();
        $body = $req->body->getValue();
        $user = Application::$user;
        $post = Post::find($body->postId->getValue());
        $imgs = [];
        if($req->files->getValue()->items()){
            $imgs = new NewImages();
            $imgs = $imgs->execute();
        }
        $answerData = new AnswerData(user: $user , post: $post , text: $body->text->getValue() , images_ids: $imgs);
        $answer = Answer::create($answerData);
        if($answer) return $answer;
        else return false;
    }
}