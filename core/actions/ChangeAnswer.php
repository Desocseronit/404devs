<?php namespace core\actions;
require_once 'NewImages.php';
require_once 'DeleteImages.php';

use core\{Response, Database , Answer, Image};

class ChangeAnswer{
    public function execute($req){
        $body = $req->getInfo()->body->getValue();
        $answer = Answer::find($body->answerId->getValue());
        if($body->userId->getValue() != $answer->user_id){
            $resp = new Response(403);
            $resp->send();
            exit;
        }
        $resBody = [];

        $originalAnswerImagesRecords = $answer->getImages();
        $originalImages = [];
        foreach(json_decode($originalAnswerImagesRecords->stringify()) as $record){
            $originalImages[] = Image::findById($record->img_id)->name;
        }
        $imagesToDelete = array_diff($originalImages , explode(',',$body->newImages->getValue()));
        if(isset($req->getInfo()->files)){
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

        $newValues = [];
        foreach($body as $key=>$val){
            $newValues[$key] = $val->getValue();
        }
        Database::instance()->updateRecord('answers' , $newValues , 'id = $1' , [$answer->id]);

        $imagesPaths = [];
        foreach($answer->getImages()->items() as $img){
            $imgId = Database::instance()->getOne('answer_images' , (int)$img->getValue()->id)->img_id;
            $imgPath = Database::instance()->getOne('images' , $imgId)->path;
            $imagesPaths[] = $imgPath; 
        }

        $resBody['images'] = $imagesPaths;
        $resBody["answer"] = $answer->getInfo();

        $res = new Response(200 , $resBody);
        $res->send();
    }
}