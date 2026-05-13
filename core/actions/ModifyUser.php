<?php namespace core\actions;
require_once('NewImages.php');
use core\{Application, Response, User, Database};

class ModifyUser{
    public function execute(){
        $req = Application::$request->getInfo();
        $body = (array)json_decode($req->body->getValue()->stringify());
        $user = Application::$user;
        if($req->files->getValue()->items()){
            $imgId = new NewImages();
            $imgId = $imgId->execute();
            $body['newAvatar'] = $imgId;
        }
        if(isset($body['password'])){
            $body['password_hash'] = password_hash($body['password'],PASSWORD_DEFAULT);
            unset($body['password']);
        }
        if($user){
            $user->modify($body);
            return true;
        }
        return false;
    }
}