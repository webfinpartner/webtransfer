<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Exchanges extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('form');
        $data = array(
            'title', 'info', 'foto',  'lang', 'description', 'url','source_url');
        $setting = array('ctrl' => 'exchanges', 'table' => 'exchanges_list',
            'argument' => $data, 'image' => "exchanges"
        );
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Обменник",
            "many" => "Обменники",
            "fields" => array(
                "Картинка" => "<img src=\"/upload/imager.php?src={$this->image_folder}/{foto}&w=60\" />",
                "Заголовок" => "title",
                "Текст" => "{function:cutString(info:45)}"));
    }
}
