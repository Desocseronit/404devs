<?php namespace core\actions;
require_once('NewImages.php');

use core\{Application,Response,Database,Post,PostData,User};



class NewPost{
    public function execute(){
        $req = Application::$request->getInfo();
        $body = $req->body->getValue();
        $user = Application::$user;
        $imgs = [];
        if(isset($req->files) && $req->files->getValue()->items()){
            $imgs = new NewImages();
            $imgs = $imgs->execute();
        }
        $postData = new PostData(user: $user , title: $body->title->getValue() , text: $body->text->getValue() , category_id: $body->category_id->getValue() , level_id: $body->level_id->getValue() , images_ids: $imgs);
        $post = Post::create($postData);
        if($post) return $post;
        return false;
    }
}