<?php namespace core\actions;
require_once('NewImages.php');

use core\{Response,Database,Post,PostData,User};



class NewPost{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId->getValue());
        $imgs = [];
        if($req->getInfo()->files->getValue()){
            $imgs = new NewImages();
            $imgs = $imgs->execute($req);
        }
        $postData = new PostData(user: $user , title: $body->title->getValue() , text: $body->text->getValue() , category_id: $body->category_id->getValue() , level_id: $body->level_id->getValue() , images_ids: $imgs);
        $post = Post::create($postData);
        $response = new Response(201 , ['post' => $post]);
        $response->send();
    }
}