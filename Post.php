<?php namespace core;

class Post{
    static private $allowedKeys = ['user' , 'title' , 'text' , 'category' , 'level' , 'images']

    public $record;
    

    public function __construct($postData){ //$user , $title , $text , $category , $level , $images = null
        foreach(Post::allowedKeys as $key){
            if(array_key_exists())
        } 
        $this->record = Database::instance()->insertRecord()
    }
}