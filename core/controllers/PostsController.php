<?php namespace controllers;
require_once(__DIR__.'/../View.php');
class PostsController{
    public function allPosts($params){
        $page = $params['page'];
        echo "its all posts page ( $page )";
        // \view\View::renderView(['test' => $page] , '/test.php');
    }
}