<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Banner extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('form');
        $data = array('title', 'text', 'foto', 'section', 'button', 'url');
        $setting = array('ctrl' => 'banner', 'table' => 'banner',
            'argument' => $data, 'image' => "banner"
        );
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Баннер",
            "many" => "Баннерами",
            "fields" => array(
                "Картинка" => "<img src=\"/upload/imager.php?src={$this->image_folder}/{foto}&w=60\" />",
                "Заголовок" => "title", "Раздел" => "section",
                "Текст" => "{function:cutString(text:45)}", "Текст кнопки" => "button"));
    }

    public function all() {
        $this->data['items'] = $this->db->select('b.*, if(p.title is NULL , "Без раздела", p.title) as section', false)
                ->from($this->table . " b")->join('pages p', "p.id_page=b.section", 'left')->get()->result();
        parent::all();
    }

}
