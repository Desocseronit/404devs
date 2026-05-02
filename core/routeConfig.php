<?php
return [
    //[type , uri , controller , viewName , dependencies [paramName => defaultValue]]
    ['type'=>'GET' , 'uri'=>'allPosts' , 'controller'=>'PostsController' , 'view'=>'allPosts' , 'dependencies' => ['page' => 1]]
];