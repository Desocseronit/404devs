<?php namespace core\actions;
require_once 'NewImages.php';

use core\{Response, Database , Post};

class ChangePost{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $post = Post::find($body->postId->getValue());
        $newData = $body;
        if(isset($newData[''])) // получить с фронта массив изначальных картинок и новых, и при сравнении если есть в старом, но нет в новом, удалить \ если есть в новом, но нет в старом, добавить (при удалении подтерать все нахуй)
        
    }
}