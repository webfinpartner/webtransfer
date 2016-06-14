<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class SimpleImage{

    var $image;
    var $image_type;

    function load($filename){
        $image_info       = getimagesize($filename);
        $this->image_type = $image_info[2];
        if($this->image_type == IMAGETYPE_JPEG){
            $this->image = imagecreatefromjpeg($filename);
        } elseif($this->image_type == IMAGETYPE_GIF){
            $this->image = imagecreatefromgif($filename);
        } elseif($this->image_type == IMAGETYPE_PNG){
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type = 2, $compression = 100, $permissions = 0766){
        if($image_type == 2){
            imagejpeg($this->image, $filename, $compression);
        } elseif($image_type == 1){
            imagegif($this->image, $filename);
        } elseif($image_type == 3){
            imagepng($this->image, $filename);
        }
        if($permissions != null){
            chmod($filename, $permissions);
        }
    }

    function output($image_type = IMAGETYPE_JPEG){
        if($image_type == IMAGETYPE_JPEG){
            imagejpeg($this->image);
        } elseif($image_type == IMAGETYPE_GIF){
            imagegif($this->image);
        } elseif($image_type == IMAGETYPE_PNG){
            imagepng($this->image);
        }
    }

    function getWidth(){
        return imagesx($this->image);
    }

    function getHeight(){
        return imagesy($this->image);
    }

    function resizeToHeight($height){
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width){
        $ratio  = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale){
        $width  = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height){
        $new_image   = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

}

class image{

    public $place;

    public function name_chack($img_name){
        $ci       = & get_instance();
        $ci->load->helper('translit');
        $img_name = translitIt($img_name);

        // проверка существованиятакого же названия


        if(file_exists("{$this->place}".$img_name)){
            $file1 = explode(".", $img_name);
            $i     = 0;
            do{
                $i++;
                $file_t = $file1[0]."($i).".$file1[1];
            } while(file_exists("{$this->place}".$file_t));
            $img_name = $file_t;
        }
        return $img_name;
    }

    public function add_foto($image){

        if(empty($image) or ! is_array($image))
            return;

        $file            = $image['file'];
        $image['name'] = $this->name_chack($image['name']);

        if(empty($this->simple) || ( isset($image['type']) && $image['type'] == 4)){
            $uploadfile = $this->place.$image['name'];
            move_uploaded_file($file['tmp_name'], $uploadfile);
        } else {
            $images = new SimpleImage;
            $images->load($file['tmp_name']);
            $images->resize($images->getWidth(), $images->getHeight());
            $images->save($this->place.$image['name']);
        }

        return $image['name'];
    }

    public function check_size_false($size){
        if($size > 1024 * 1024 * 1 - 50)
            return true;
        else
            return false;
    }

    public function create_name($name, $md5 = false){
        $name = explode(".", $name);
        return ($md5 == true) ? md5($name[0].'-'.time()) : str_replace("[^A-Za-zа-яА-Я0-9]", "", $name[0]);
    }

    public function get_ext($mime){
        switch($mime){
            case 'image/pjpeg':
            case 'image/jpeg':
                return ['ext'  => 'jpg', 'type' => 2];
            case 'image/gif':
                return ['ext'  => 'gif', 'type' => 1];
            case 'image/png':
                return ['ext'  => 'png', 'type' => 3];
            default:
                return ['ext'  => '', 'type' => 0];
        }
    }

    public function file($file, $md5 = false){
        if(!empty($_FILES[$file]['tmp_name'])){

            // проверка расширения
            // проверка типа
            $imageinfo = getimagesize($_FILES[$file]['tmp_name']);

            if($imageinfo === FALSE && isset($_FILES[$file]['type'])
                && $_FILES[$file]['type'] == 'application/pdf'){
                $ext  = 'pdf';
                $type = 4;
            } else {
                $r = $this->get_ext($imageinfo['mime']);
                $ext  = $r['ext'];
                $type = $r['type'];
            }
            if($this->check_size_false($_FILES[$file]['size']))
                return 3;


            if($ext){
                $name = $this->create_name($_FILES[$file]['name'], $md5);
                return [ 'name' => "$name.$ext", 'type' => $type, 'file' => $_FILES[$file]];
            } else
                return 2;
        } else
            return 4;
        if(@$_FILES[$file]['error'] == 2 or @ $_FILES[$file]['error'] == 1)
            return 3;
        if(@$_FILES[$file]['error'] == 3 or @ $_FILES[$file]['error'] == 4)
            return 1;
    }

##########################################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
##########################################################################################################

    function resizeImage($image, $width, $height, $scale){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType      = image_type_to_mime_type($imageType);
        $newImageWidth  = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage       = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch($imageType){
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

        switch($imageType){
            case "image/gif":
                imagegif($newImage, $image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $image, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $image);
                break;
        }

        chmod($image, 0666);
        return $image;
    }

//You do not need to alter these functions
    function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth  = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage       = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch($imageType){
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch($imageType){
            case "image/gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0666);
        return $thumb_image_name;
    }

//You do not need to alter these functions
    function getHeight($image){
        $size   = getimagesize($image);
        $height = $size[1];
        return $height;
    }

//You do not need to alter these functions
    function getWidth($image){
        $size  = getimagesize($image);
        $width = $size[0];
        return $width;
    }

    function getImageContentWithWarterMark($img_src_content, $watermark_text, $watermark_color = '#000', $watermark_opacity = 0.2, $watermark_font_size = 25){

        if(empty($img_src_content) || empty($watermark_text))
            return FALSE;

        if(!class_exists('Imagick') || !class_exists('ImagickDraw') || !class_exists('ImagickPixel')){
            echo "There is some technical troubles! Please, appeal to our support team.";
//            phpinfo();
            die;
        }

        // Create objects
        $image = new Imagick();
        $image->readimageblob($img_src_content);

        $watermark = new Imagick();

        // Create a new drawing palette
        $draw = new ImagickDraw();
        $watermark->newImage(430, 200, new ImagickPixel('none'));
        // Set font properties
//        $draw->setFont('arial.ttf');

        $draw->setFontSize($watermark_font_size);
        $draw->setFillColor($watermark_color);

        $draw->setFillOpacity($watermark_opacity);


        // Position text at the top left of the watermark
        $draw->setGravity(Imagick::GRAVITY_NORTHWEST);
        $watermark->annotateImage($draw, 10, 0, 20, $watermark_text);

        $watermark->setImageFormat('jpg');
        $image->setImageFormat('jpg');
        header("Content-Type: image/jpg");

//         Repeatedly overlay watermark on image
        for($w = 0; $w < $image->getImageWidth(); $w += 230){
            for($h = 0; $h < $image->getImageHeight(); $h += 150){
                $image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $w, $h);
            }
        }


        return $image;
    }

    function combineImages($img_src_content_array){
        /* Crée un nouvel objet imagick */
        $im = new Imagick();

        $count = 0;
        foreach($img_src_content_array as $img_src_content){
            if(empty($img_src_content) || count($img_src_content) === 0)
                continue;

            $im->readimageblob($img_src_content);
            $count++;
        }

        if($count === 0)
            return FALSE;

        $im->resetIterator();
        $combine = $im->appendImages(true);
        $combine->setImageFormat('jpg');
        header("Content-Type: image/jpg");
        return $combine;
    }

}
