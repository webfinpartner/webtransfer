<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!empty($videos)){
    $size = "default";
    foreach ($videos as $video)
        $this->load->view('user/blocks/renderVideo_part.php', compact("video","size"));
}
