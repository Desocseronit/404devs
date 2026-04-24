<?php namespace core\actions;

use core\{Response};
use core\{Database};
use core\{Post};
use core\{User};


class NewPost{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId);
        $imgs;
        if($req->getInfo()->files){
            $imgs = new NewImages();
            $imgs = $imgs->execute($req);
        }
        $postData = new PostData(user: $user , title: $body->title , text: $body->text , category_id: $body->categoty_id , level_id: $body->level_id , images_ids: $imgs);
        $post = Post::create($postData);
        $response = new Response(201 , ['post' => $post]);
        $response->send();
    }
}