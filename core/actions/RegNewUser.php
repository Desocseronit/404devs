<?php namespace core\actions;

use core\{Application,Response,User, UserData,Database};


class RegNewUser{
    public function execute(){
        $requestBody = Application::$request->getInfo()->body->getValue();
        $userData = new UserData(name: $requestBody->username->getValue() , password: $requestBody->password->getValue() , email: $requestBody->email->getValue());
        if(!User::checkIfUserExist($userData)){
            $user = User::reg($userData);
            if($user) return $user->stringify();
        }
        return false;
    }
}