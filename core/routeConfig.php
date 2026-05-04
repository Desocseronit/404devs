<?php
require_once 'Application.php';
use core\{Application};
return [
    //[type , uri , controller , viewName , dependencies [paramName => defaultValue] , auth = null (if need auth)]
    ['type'=>'GET' , 'uri'=>'allPosts' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => 1] ],
    ['type' => 'GET' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'loginRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'authUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'registrate' , 'controller' => 'UserController' , 'view' => 'regRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'registrate' , 'controller' => 'UserController' , 'view' => 'regUser' , 'dependencies' => []],
    ['type' => 'GET' , 'uri'=>'profile' , 'controller' => 'UserController' , 'view' => 'profileRender' , 'dependencies' => ['id'  => Application::$user->id]],
    'errors' => [['error' => 404 , 'view' => 'render404']]
];