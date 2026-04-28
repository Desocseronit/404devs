<?php namespace core\actions;
require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Response, Database , Post, Image};

class ChangePost{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $post = Post::find($body->postId->getValue());
        unset($body->postId);
        $resBody = [];
        // получить с фронта массив изначальных картинок и новых, и при сравнении если есть в старом, но нет в новом, удалить \ если есть в новом, но нет в старом, добавить (при удалении подтерать все нахуй)
        if(isset($body->newImages) && isset($body->originalImages)){
            $toDelete = array_diff($body->originalImages, $body->newImages);
            $toAdd = array_diff($body->newImages, $body->originalImages);
            $toKeep = array_intersect($body->originalImages, $body->newImages);

            if(!empty($toDelete)){
                $deleteImgs = new DeleteImages();
                $deleteImgs->execute($toDelete);
            }
            $postImages = [];
            if(!empty($toAdd)){
                $newImgs = new NewImages();
                $newImgs = $newImgs->execute($req);
                foreach($newImgs as $img){
                    $postImages[] = Database::instance()->insertRecord('post_images' , ['post_id' => $post->id , 'img_id' => $img]);
                }
            }

            if(!empty($toKeep)){
                foreach($toKeep as $img){
                    $postImages[] = Image::findByName($img);
                }
            }
            unset($body->newImages);
            unset($body->items()->newImages);
            unset($body->originalImages);
            unset($body->items()->originalImages);
            $resBody['images'] = $postImages;
        }

        foreach($body->items() as $key=>$val){
            $post->$key = $val;
        }
        $post->record->update();

        $resBody['post'] = $post;

        $res = new Response(200 , $resBody);
        $res->send();
    }
}