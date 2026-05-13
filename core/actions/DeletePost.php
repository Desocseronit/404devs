<?php namespace core\actions;
use core\{Application , Database , Post , Answer, Image , Response};
require_once ('DeleteImages.php');
class DeletePost{
    public function execute(){
        $body = Application::$request->getInfo()->body->getValue();
        if(isset($body->postId)){
            $post = Post::find($body->postId->getValue());
            $imgs = $post->getImages();
            $imgsNames = [];
            foreach($imgs->items() as $imgId){
                $imgsNames[] = Image::findById($imgId)->name;
            }
            $deleteImgs = new DeleteImages();
            $deleteImgs->execute($imgsNames);
            $post->delete();
            return true;
        }
        return false;
    }
}