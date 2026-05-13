<?php namespace core\actions;
use core\{Application , Database , Answer, Image , Response};
require_once ('DeleteImages.php');
class DeleteAnswer{
    public function execute(){
        $body = Application::$request->getInfo()->body->getValue();
        if(isset($body->answerId)){
            $answer = Answer::find($body->answerId->getValue());
            $imgs = $answer->getImages();
            $imgsNames = [];
            foreach($imgs->items() as $imgId){
                $imgsNames[] = Image::findById($imgId->getValue()->img_id)->name;
            }
            $deleteImgs = new DeleteImages();
            $deleteImgs->execute($imgsNames);
            $answer->delete();
            return true;
        }
        return false;
    }
}