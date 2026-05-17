<?php namespace core\actions;

require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Application ,Response, Database, Post, Image};

class ChangePost {
    public function execute(){
        $req = Application::$request->getInfo();
        $body = $req->body->getValue();
        $post = Post::find($body->postId->getValue());
        if(Application::$user->id != $post->user_id){
            return false;
        }
        $resBody = [];

        $originalPostImagesRecords = $post->getImages();
        $originalImages = [];
        foreach(json_decode($originalPostImagesRecords->stringify()) as $record){
            $originalImages[] = Image::findById($record->img_id)->name;
        }
        $imagesToDelete = array_diff($originalImages , explode(',',$body->newImages->getValue()));

        if(isset($req->files)){
            $newImageAction = new NewImages();
            $newImageAction = $newImageAction->execute($req);
            foreach($newImageAction as $img){
                $post->addImage($img);
            }
        }
        
        $deleteImagesAction = new DeleteImages();
        $deleteImagesAction->execute($imagesToDelete);

        unset($body->newImages);
        unset($body->postId);
        unset($body->userId);

        $newValues = [];
        foreach($body as $key=>$val){
            $newValues[$key] = $val->getValue();
        }
        return Database::instance()->updateRecord('posts' , $newValues , 'id = $1' , [$post->id]);
    }
}