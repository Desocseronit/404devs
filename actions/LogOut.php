<?php namespace core\actions;

use core\{Response,User}
class LogOut{
    public function execute($req){
        $body = $req->getInfo()->body->getValue()
        $userId = User::find($body->userId->getValue());

        if($user){
            $userId->logOut();
            return new Rewponse([200])
        }
        else{
            return new Response([400])
        }
        
    }
}