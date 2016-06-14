<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Faq extends Admin_controller {

    public function __construct() {
        parent::__construct();
        $data = array('position', 'question', 'answer');
        $setting = array('ctrl' => 'faq', 'view' => 'faq', 'table' => 'faq', 'argument' => $data,);
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "новый",
            "many" => "FAQ",
            "fields" => array(
                "Позиция" => "position", "Вопрос" => "question", "Ответ" => "answer",
        ));
    }

}
