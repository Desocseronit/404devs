<?php  namespace core\actions;
use core\{Image};

class NewImages{
    public function execute($req){
        $ids = [];

        $filesData = $req->getInfo()->files->getValue();
        if (!$filesData) {
            return $ids;
        }
        foreach($filesData->items() as $file){
            if (!$file || 
                !isset($file->getValue()['tmp_name']) || 
                $file->getValue()['tmp_name'] === '' ||
                (isset($file->getValue()['error']) && $file->getValue()['error'] !== UPLOAD_ERR_OK) ||
                !is_uploaded_file($file->getValue()['tmp_name'])) {
                continue;
            }
            $img = new Image($file->getValue());
            $ids[] = $img->id;
        }
        return $ids;
    }
}