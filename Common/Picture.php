<?php

  
class Picture{
    public $fileName;
    public $id;

    public static function getPictures(){
        $picture = array();
        $files = scandir(THUMBNAIL_DESTINATION);      
        $numFiles = count($files);

        if($numFiles > 2){
            for($i=2; $i < $numFiles; $i++){
                $ind = strrpos($files[$i], "/");
                $fileName = substr($files[$i], $ind);
                $picture = new Picture($fileName, $i);
                $pictures["$i"] = $picture;
            }
        }
        return $pictures;
    }

    public static function getPictureById($id) {
        $pictures = self::getPictures();
        foreach($pictures as $picture)
        {
            if($picture->id == $id) { return $picture; }
        }
        return false;
    }

    public function __construct($fileName, $id){
        $this->fileName = $fileName;
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        $ind = strrpos($this->fileName, ".");
        $name = substr($this->fileName, 0, $ind);
        return $name;  
    }

    public function getAlbumFilePath(){
        return IMAGE_DESTINATION."/".$this->fileName;
    }

    public function getThumbnailFilePath(){
        return THUMBNAIL_DESTINATION."/".$this->fileName;
    }

    public function getOriginalFilePath(){
        return ORIGINAL_IMAGE_DESTINATION."/".$this->fileName;
    }
}
