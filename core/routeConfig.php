<?php
return [
    //[type , uri , controller , viewName , dependencies [paramName => defaultValue]]
    ['type'=>'GET' , 'uri'=>'allPosts' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => 1]],
    ['type' => 'GET' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'loginRender' , 'dependencies' => []],
    ['type' => 'POST' , 'uri'=>'login' , 'controller' => 'UserController' , 'view' => 'authUser' , 'dependencies' => []],
];