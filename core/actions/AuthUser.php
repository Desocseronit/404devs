<?php namespace core\actions;

use core\{Response,User,Application};

class AuthUser{
    public function execute(){
        $body = Application::$request->getInfo()->body->getValue();
        $user = User::authByCredentials($body->username->getValue() , $body->password->getValue());
        if($user){
            return $user->record->stringify();
        }
        return false;
    }
}