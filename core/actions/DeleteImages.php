<?php namespace core\actions;

use core\{Image,Database};

class DeleteImages{
    public function execute($names){ // создать в images столбец name , в котором лежит только имя картинки
        foreach($names as $name){
            $id = Image::findByName($name);
            Database::instance()->deleteRecord('post_images', 'id = $1' , [$id]);
            Database::instance()->deleteRecord('answer_images', 'id = $1' , [$id]);
            Image::delete('id' , $id);
        }
        return true
    }
}