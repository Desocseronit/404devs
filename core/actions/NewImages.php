<?php  namespace core\actions;
use core\{Image};

class NewImages{
    public function execute($req){
        $ids = [];
        foreach($req->getInfo()->files->getValue()->items() as $file){
            $img = new Image($file->getValue());
            $ids[] = $img->id;
        }
        return $ids;
    }
}