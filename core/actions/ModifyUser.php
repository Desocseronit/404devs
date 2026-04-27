<?php namespace core\actions;
use core\{Response,User,Database};

class ModifyUser{
    public function execute($req){
        $res;
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->UserId->getValue());

        if($user){
            $user->modify();
            return New Response([200])
        }
        else{
            return New Response([400],{'message'''})
        }
        
    }
}