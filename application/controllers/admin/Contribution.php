<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Contribution extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $data = array('title', 'percent', 'month', "charge", 'bonus', 'position');

        $setting = array('ctrl' => 'contribution',
            'view' => 'contribution',
            'table' => 'contribution',
            'argument' => $data);
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Тип  вклада",
            "many" => "Типами  вкладов",
            "fields" => array(
                "Название" => "title", "Срок" => "month", "Процент" => "percent", "Начисление" => "{function:contribution(charge)}", "Бонус" => "bonus",
        ));
    }

}

function contribution($state) {
    if ($state == 1)
        return "Ежемесячно";
    else
        return "В конце срока";
}
