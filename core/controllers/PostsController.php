<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../Application.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
require_once(__DIR__.'/../actions/NewPost.php');
use core\{Application , Response , Database , Post};
use core\actions\{NewPost};
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

    public function createPostsRender(){
        if(Application::$user) \view\View::renderView(path: '/createPost.php');
        else {
            require_once('ErrorController.php');
            $errorConInstance = new ErrorController();
            $errorConInstance->render401();
        }
    }

    public function createPost(){
        $actionInstance = new NewPost();
        $res = $actionInstance->execute();
        $resp;
        if(!$res){
            $resp = new Response(400);
        }
        else{
            $resp = new Response(200 , ['postId' => $res->id]);
        }
        $resp->send();
    }
}