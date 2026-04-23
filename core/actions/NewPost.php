<?php namespace core\actions;

use core\{Response};

class NewPost{
    public function execute(){
        $test = new Response(200, ['message' => 'penis']);
        $test -> send();
    }
}