<?php namespace controllers;
require_once(__DIR__.'/../View.php');
require_once(__DIR__.'/../Application.php');
require_once(__DIR__.'/../modules/Database.php');
require_once(__DIR__.'/../modules/Post.php');
require_once(__DIR__.'/../modules/Answer.php');
use core\{Application , Database , Post , Answer};
class AnswersController{
    public function postRender($params){
        $post = Post::find((int)$params['id']);
        $page = $params['page'];
        $filterBy = $params['filterby'];
        $side = (bool)$params['side'];
        $idSide = (bool)$params['idSide'];
        $data = Answer::paginate($page , Application::$CONFIG['publicationsPerPage'] , $filterBy, $side , $idSide , (int)$params['id']);
        $data['parentPost'] = $post->getInfo();
        \view\View::renderView(['test' => $data] , '/test.php');
    }
}