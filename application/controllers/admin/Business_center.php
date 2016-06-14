<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Business_center extends Admin_controller {

    public function __construct() {
        parent::__construct();
        $data = array('title', 'type');

        $setting = array('ctrl' => 'business_center',
            'view' => 'business_center',
            'table' => 'business_center',
            'argument' => $data);
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Безнес центр",
            "many" => "Безнес центрами",
            "fields" => array("Название" => "title", 'Тип' => 'type'));
    }

    function update() {
        $result = $this->db->get($this->table)->result();
        foreach ($result as $item) {
            if (preg_match('/\(A\)/sui', $item->title)) {
                $type = 'A';
                $item->title = preg_replace('/\(A\)/sui', '', $item->title);
            } else if (preg_match('/\(B\)/sui', $item->title)) {
                $type = 'B';
                $item->title = preg_replace('/\(B\)/sui', '', $item->title);
            } else
                $type = '';


            $this->db->where('id', $item->id)->update($this->table, array('title' => $item->title, 'type' => $type));
        }
    }

}
