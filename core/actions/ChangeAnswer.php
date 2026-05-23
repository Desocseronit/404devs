<?php namespace core\actions;
require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Application, Response, Database , Answer, Image};

class ChangeAnswer{
    public function execute(){
        $req = Application::$request->getInfo();
        $body = $req->body->getValue();
        $answer = Answer::find($body->answerId->getValue());
        if(Application::$user->id != $answer->user_id){
            return false;
        }
        $resBody = [];

        $originalAnswerImagesRecords = $answer->getImages();
        $originalImages = [];
        foreach(json_decode($originalAnswerImagesRecords->stringify()) as $record){
            $originalImages[] = Image::findById($record->img_id)->name;
        }
        $imagesToDelete = array_diff($originalImages , explode(',',$body->newImages->getValue()));
        if(isset($req->files)){
            $newImageAction = new NewImages();
            $newImageAction = $newImageAction->execute($req);
            foreach($newImageAction as $img){
                $answer->addImage($img);
            }
        }
        
        $deleteImagesAction = new DeleteImages();
        $deleteImagesAction->execute($imagesToDelete);

        unset($body->newImages);
        unset($body->answerId);
        unset($body->userId);

        $newValues = ['is_changed' => true];
        foreach($body as $key=>$val){
            $newValues[$key] = $val->getValue();
        }

        return Database::instance()->updateRecord('answers' , $newValues , 'id = $1' , [$answer->id]);
    }
}