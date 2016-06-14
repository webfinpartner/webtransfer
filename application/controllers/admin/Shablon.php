<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Shablon extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $data = array(
            'sh_title' => 'title',
            'sh_content' => 'text',
            'lang',
            'master',
        );
        $this->id = "id_shablon";
        $setting = array('ctrl' => 'shablon', 'view' => 'shablon', 'table' => 'shablon', 'argument' => $data,);
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Шаблон",
            "many" => "Шаблонами",
            "fields" => array(
                "Заголовок" => "sh_title", "Текст" => "{function:cutString(sh_content:100)}",
        ));
    }

    public function delete($id) {

    }

}
