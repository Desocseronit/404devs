<?php namespace core\actions;

require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Response, Database, Post, Image};

class ChangePost {
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $post = Post::find($body->postId->getValue());
        if($body->userId->getValue() != $post->user_id){
            $resp = new Response(403);
            $resp->send();
            exit;
        }
        $resBody = [];

        $originalPostImagesRecords = $post->getImages();
        $originalImages = [];
        foreach(json_decode($originalPostImagesRecords->stringify()) as $record){
            $originalImages[] = Image::findById($record->img_id)->name;
        }
        $imagesToDelete = array_diff($originalImages , explode(',',$body->newImages->getValue()));

        if(isset($req->getInfo()->files)){
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
        Database::instance()->updateRecord('posts' , $newValues , 'id = $1' , [$post->id]);

        $imagesPaths = [];
        foreach($post->getImages()->items() as $img){
            $imgId = Database::instance()->getOne('post_images' , (int)$img->getValue()->id)->img_id;
            $imgPath = Database::instance()->getOne('images' , $imgId)->path;
            $imagesPaths[] = $imgPath; 
        }

        $resBody['images'] = $imagesPaths;
        $resBody["post"] = $post->getInfo();

        $res = new Response(200 , $resBody);
        $res->send();
    }
}