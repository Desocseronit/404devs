<?php namespace core\actions;

require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Response, Database, Post, Image};

class ChangePost {
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        // в пизду все проверки
        //мне прилетает в newImages только имена картинок которые были до этого в посте, после я удаляю все чьих имен нет в списке
        // новые картинки все лежат у меня в files
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

        if(isset($req->files)){
            $newImageAction = new NewImages();
            $newImageAction = $newImageAction->execute($req);
            foreach($newImageAction as $img){
                $post->addImage($img)->path;
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
        // echo '<pre>';
        // var_dump($imagesPaths);
        // exit;
        // $post = Post::find($body->postId->getValue());
        
        // $resBody = [];
        
        // if($body->has('newImages') && $body->has('originalImages')){
        //     $originalImages = explode(',',$body->originalImages->getValue());
        //     $newImages = explode(',',$body->newImages->getValue());          
            
        //     $toDelete = array_diff($originalImages, $newImages);
        //     $toAdd = array_diff($newImages, $originalImages);
        //     $toKeep = array_intersect($originalImages, $newImages);
            
        //     if(!empty($toDelete)){
        //         $deleteImgs = new DeleteImages();
        //         $deleteImgs->execute($toDelete);
        //     }
            
        //     $newImageIds = [];
        //     if(!empty($toAdd)){
        //         $newImgsAction = new NewImages();
        //         $uploadedIds = $newImgsAction->execute($req); 

        //         foreach($uploadedIds as $imgId){
        //             Database::instance()->insertRecord('post_images', [
        //                 'post_id' => $post->id,
        //                 'img_id' => $imgId
        //             ]);
        //             $newImageIds[] = $imgId;
        //         }
        //     }
            
        //     $keepImageIds = [];
        //     foreach($toKeep as $imgName){
        //         $imgId = Image::findByName($imgName);
        //         if($imgId){
        //             $keepImageIds[] = $imgId;
        //         }
        //     }
            
        //     $resBody['images'] = array_merge($keepImageIds, $newImageIds);
        // }
        
        // $skipFields = ['postId', 'newImages', 'originalImages'];
        // foreach($body->items() as $key => $collectionElement){
        //     if(!in_array($key, $skipFields)){
        //         $post->$key = $collectionElement->getValue();
        //     }
        // }
        // $post->record->update();
        
        // $resBody['post'] = $post;
        
        // $res = new Response(200, $resBody);
        // $res->send();
    }
}