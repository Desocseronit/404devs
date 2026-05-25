<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../Application.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
require_once(__DIR__.'/../actions/NewPost.php');
require_once(__DIR__.'/../actions/ChangePost.php');
require_once(__DIR__.'/../actions/DeletePost.php');
use core\{Application , Response , Database , Post};
use core\actions\{NewPost ,ChangePost,DeletePost};
class PostsController{
    public function allPosts($params){
        $page = $params['page'];
        $title = $params['title'];
        $filterBy = $params['filterby'];
        $category = Database::instance()->getOne('categories' , $params['category'] , 'name')->id;
        $level = $params['level'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Post::paginate($page , Application::$CONFIG['publicationsPerPage'] , $filterBy , $side , $idSide , $category , $level , title : $title);
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
        if(!Application::$user){
            require_once('ErrorController.php');
            $errorConInstance = new ErrorController();
            $errorConInstance->render401();
            return;
        }
        $actionInstance = new NewPost();
        $res = $actionInstance->execute();
        $resp;
        if(!$res){
            $resp = new Response(400);
        }
        else{
            $resp = new Response(201 , ['postId' => $res->id]);
        }
        $resp->send();
    }

    public function votePost($params){
        $reqBody = Application::$request->getInfo()->body->getValue();
        $id = $reqBody->id->getValue();
        $vote = $reqBody->vote->getValue();
        $resp;
        $post = Post::find($id);
        $post->updateLikeStatus();
        if($post->isLiked){
            $resp = new Response(400);
            $resp->send();
            return;
        } 
        $post->vote($vote);
        if($post->isLiked) $resp = new Response(200);
        else $resp = new Response(400);
        $resp->send();
    }

    public function changePost(){
        if(!Application::$user){
            require_once('ErrorController.php');
            $errorConInstance = new ErrorController();
            $errorConInstance->render401();
            return;
        }
        $actionInstance = new ChangePost();
        $res = $actionInstance->execute();
        $resp;
        if(!$res){
            $resp = new Response(400);
        }
        else{
            $resp = new Response(200);
        }
        $resp->send();
    }

    public function deletePost(){
        if(!Application::$user){
            require_once('ErrorController.php');
            $errorConInstance = new ErrorController();
            $errorConInstance->render401();
            return;
        }
        $actionInstance = new DeletePost();
        $res = $actionInstance->execute();
        $resp;
        if(!$res){
            $resp = new Response(400);
        }
        else{
            $resp = new Response(200);
        }
        $resp->send();
    }
}