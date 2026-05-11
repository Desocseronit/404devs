<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../Application.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
require_once(__DIR__.'/../modules/Answer.php');
require_once(__DIR__.'/../actions/NewAnswer.php');
use core\{Application , Response , Database , Post , Answer};
use core\actions\{NewAnswer};
class AnswersController{
    public function postRender($params){
        $post = Post::find((int)$params['id']);
        $post->incrementViews();
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Answer::paginate($page , Application::$CONFIG['publicationsPerPage'] , $filterBy, $side , $idSide , (int)$params['id']);
        $data['parentPost'] = $post->getInfo();
        \view\View::renderView(['test' => $data] , '/test.php');
    }

    public function addAnswer(){
        if(!Application::$user){
            require_once('ErrorController.php');
            $errorConInstance = new ErrorController();
            $errorConInstance->render401();
            return;
        }

        $actionInstance = new NewAnswer();
        $res = $actionInstance->execute();
        $resp;
        if($res){
            $resp = new Response(201);
        }
        else{
            $resp = new Response(400);
        }
        $resp->send();
    }

    public function voteAnswer($params){
        $reqBody = Application::$request->getInfo()->body->getValue();
        $id = $reqBody->id->getValue();
        $vote = $reqBody->vote->getValue();
        $resp;
        $answer = Answer::find($id);
        $answer->updateLikeStatus();
        if($answer->isLiked){
            $resp = new Response(400);
            $resp->send();
            return;
        } 
        $answer->vote($vote);
        if($answer->isLiked) $resp = new Response(200);
        else $resp = new Response(400);
        $resp->send();
    }
}