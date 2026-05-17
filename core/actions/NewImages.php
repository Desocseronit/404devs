<?php  namespace core\actions;
use core\{Application ,Image};

class NewImages{
    public function execute(){
        $ids = [];

        $filesData = Application::$request->getInfo()->files->getValue();
        $key = $filesData->keys();
        if (!$filesData) {
            return $ids;
        }
        foreach($filesData->items()[$key[0]]->getValue() as $file){
            if (!$file || 
                !isset($file['tmp_name']) || 
                $file['tmp_name'] === '' ||
                (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) ||
                !is_uploaded_file($file['tmp_name'])) {
                continue;
            }
            $img = new Image($file);
            $ids[] = $img->id;
        }
        return $ids;
    }
}