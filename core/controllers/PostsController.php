<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../Application.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
use core\{Application , Database , Post};
class PostsController{
    public function allPosts($params){
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $category = $params['category'];
        $level = $params['level'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Post::paginate($page , Application::$CONFIG['publicationsPerPage'] , $filterBy , $side , $idSide , $category , $level);
        \view\View::renderView(['data' => $data] , '/allPosts.php');
    }

    public function postRender($params){
        $post = Post::find((int)$params['id']);
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Answer::paginate($page , self::$perPage , $filterBy, $side , $idSide , $category , $level);
        $data['parentPost'] = $post->getInfo();
        \view\View::renderView(['test' => $data] , '/test.php');
    }
}