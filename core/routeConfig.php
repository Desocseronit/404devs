<?php
require_once 'Application.php';
use core\{Application};
return [
    //[type , uri , controller , viewName , dependencies [paramName => [defaultValue , [allowed values?]]] , auth = null (if need auth)]
    ['type'=>'GET' , 'uri'=>'main' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => [1 , []] , 'filterby' => ['votes' , []] , 'category' => [null , []]]],
    ['type'=>'GET' , 'uri'=>'all-posts' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => [1 , []] , 'filterby' => ['votes' , []] , 'category' => [null , []]]],
    ['type' => 'GET' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'loginRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'authUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'sign-up' , 'controller' => 'UserController' , 'view' => 'regRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'sign-up' , 'controller' => 'UserController' , 'view' => 'regUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'profile' , 'controller' => 'UserController' , 'view' => 'profileRender' , 'dependencies' => ['id'  => Application::$user->id ?? null]],
    'errors' => [['error' => 404 , 'view' => 'render404']]
];