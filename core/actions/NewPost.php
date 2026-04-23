<?php namespace core\actions;

use core\{Response};

class NewPost{
    public function execute($req){
        $test = new Response(200, ['message' => 'not penis)']);
        $test -> send();
    }
}