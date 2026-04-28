<?php namespace core\actions;

use core\{Image,Database};

class DeleteImages{
    public function execute($names){
        foreach($names as $name){
            $id = Image::findByName($name);
            Database::instance()->deleteRecord('post_images', 'img_id = $1' , [$id]);
            Database::instance()->deleteRecord('answer_images', 'img_id = $1' , [$id]);
            Image::delete($id);
        }
        return true;
    }
}