<?php
require_once 'Application.php';
require_once 'modules/Post.php';
use core\{Application , Post};
return [
    //[type , uri , controller , viewName , dependencies [paramName => [defaultValue , [allowed values?] , [not allowed values?]] , auth = null (if need auth)]
    
    //Posts
    ['type'=>'GET' , 'uri'=>'main' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => [1 , [] , []] , 'filterby' => ['votes' , ['votes' , 'created_at' , 'views'] , []] , 'category' => [null , ['PHP' , 'JAVA' , 'Go' , 'Pyhton' , 'Node.js' , 'HTML' , 'CSS', 'JavaScript'] , []], 'level' => [null , ['studying' , 'easy' , 'medium' , 'hard' , 'esoteric'] , []], 'side' => [0 , [0 , 1] , []] , 'idSide' => [0 , [0 , 1] , []]]],
    ['type'=>'GET' , 'uri'=>'all-posts' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => [1 , [] , []] , 'filterby' => ['votes' , ['votes' , 'created_at' , 'views'] , []] , 'category' => [null , ['PHP' , 'JAVA' , 'Go' , 'Pyhton' , 'Node.js' , 'HTML' , 'CSS', 'JavaScript'] , []], 'level' => [null , ['studying' , 'easy' , 'medium' , 'hard' , 'esoteric'] , []], 'side' => [0 , [0 , 1] , []] , 'idSide' => [0 , [0 , 1] , []]]],
    ['type' => 'GET' , 'uri'=>'create-post' , 'controller' => 'PostsController' , 'view' => 'createPostsRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'create-post' , 'controller' => 'PostsController' , 'view' => 'createPost' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'vote-post' , 'controller' => 'PostsController' , 'view' => 'votePost' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'change-post' , 'controller' => 'PostsController' , 'view' => 'changePost' , 'dependencies' => []],

    //User
    ['type' => 'GET' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'loginRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'authUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'sign-up' , 'controller' => 'UserController' , 'view' => 'regRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'sign-up' , 'controller' => 'UserController' , 'view' => 'regUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'profile' , 'controller' => 'UserController' , 'view' => 'profileRender' , 'dependencies' => ['id'  => [Application::$user->id ?? null, [], []]]],

    // Answers
    ['type' => 'GET' , 'uri'=>'view-post' , 'controller' => 'AnswersController' , 'view' => 'postRender' , 'dependencies' => ['id' => [Post::getRandomPostId() , [] , []],'page' => [1 , [] , []] , 'filterby' => ['votes' , ['votes' , 'created_at'] , []], 'side' => [0 , [0 , 1] , []] , 'idSide' => [0 , [0 , 1] , []]]],
    ['type' => 'POST' , 'uri'=>'add-answer' , 'controller' => 'AnswersController' , 'view' => 'addAnswer' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'vote-answer' , 'controller' => 'AnswersController' , 'view' => 'voteAnswer' , 'dependencies' => []],

    //errors
    'errors' => [['error' => 404 , 'view' => 'render404']]
];