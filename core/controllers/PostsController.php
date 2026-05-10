<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
use core\{Database , Post};
class PostsController{
    public function allPosts($params){
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $category = $params['category'];
        $level = $params['level'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Post::paginate($page , 20 , $filterBy , $side , $idSide , $category , $level);
        \view\View::renderView(['test' => $data] , '/test.php');
    }
}