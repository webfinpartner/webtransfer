<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class News extends Admin_controller {

    /**
     * Конструктор класса с проверкой авторизации
     */
    public function __construct() {
        parent::__construct();
        $data = array('title', 'text', 'data', 'url', 'foto', 'to_all', 'lang', 'master');
        $this->id = "id_new";
        $this->safe_url = "title";
        $setting = array('ctrl' => 'news',
            'view' => 'new',
            'table' => 'news',
            'argument' => $data,
            'image' => 'news');
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Новость",
            "many" => "Новостями Сайта",
            "fields" => array(
                "Дата" => "data",
                //"Картинка"=>"<img src=\"/upload/imager.php?src=news/{foto}&w=60\" />",
                "Заголовок" => "title", "Ссылка" => "url",
            )
        );
    }

    public function create($id = 0) {

        if ($_POST['submited']) {
            if (!isset($_POST['to_all'])) {
                $_POST['to_all'] = 0;
            }
        }

        parent::create($id);
    }

}
