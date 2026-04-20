<?php namespace core;
class Image{
    public $record;

    public function __construct($file){
        $date = new \DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        $name = md5($file['name'].microtime());
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uploadDir = "uploads/{$year}/{$month}/{$day}";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadDir .= "/{$name}.$extension";
        
        move_uploaded_file($file['tmp_name'], $uploadDir);

        $this->record = Database::instance()->insertRecord('images' , ['path' => $uploadDir]);

        foreach($this->record->attributes as $key => $val) { 
            $this->$key = &$this->record->$key;
        }
    }

    public function __set($name , $value){
        $this->$name = &$this->attributes[$name];
        return $value;
    }
    
    public function getSize(){
        return filesize($this->path);
    }
}