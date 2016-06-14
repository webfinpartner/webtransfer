<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Partner_banner extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('form');
        $data = array('title', 'lang', 'foto', 'active');
        $setting = array('ctrl' => 'partner_banner', 'table' => 'partner_banner',
            'argument' => $data, 'image' => "partner_banner"
        );
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Баннер Партнера",
            "many" => "Баннеры Партнеров",
            "fields" => array(
                "Картинка" => "<img src=\"/upload/imager.php?src={$this->image_folder}/{foto}&w=60\" />",
                "Название" => "title",
        ));
    }

}
