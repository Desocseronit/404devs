<?php namespace core\actions
    use core\{Post};
class Votes{
    public function execute ($req){
        $body = $req->getInfo()->body->getValue();
        $user = User::find($body->userId);
        if(isset($body->postId)){
            $postId = $body->post_id;
            Database::instance()->updateRecord(
                'post',
                ['votes'=>'votes + '.$body->value],
                'id = $1',
                [$postId]
            );
        }
        elseif(isset($body->answerId)){
            $answerId = $body->answerId;
            Database::instance()->updateRecord(
                'answers',
                ['votes'=>'votes + '.$body->value],
                'id = $1',
                [$answerId]
            );
        }
    }
}
