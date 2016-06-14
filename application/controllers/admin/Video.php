<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Video extends Admin_controller {

    /**
     * Конструктор класса с проверкой авторизации
     */
    public function __construct() {

        parent::__construct();
        $data = array('title',
            'category',
            'foto',
            'id_video',
            'info',
            'featured',
            'status',
            'lang',
            'id_user'
        );
        $setting = array('ctrl' => 'video',
            'view' => 'video',
            'table' => 'video',
            'argument' => $data,
            'image' => 'news');
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Видео",
            "many" => "Видео",
            "fields" => array(
                "Дата" => "date",
                "Заголовок" => "title",
                "Код видео" => "id_video",
                "action" => "{function:admin_active(status)}")
        );
    }

    private function uploadfile($input_name) {
        $uploaddir = $_SERVER['DOCUMENT_ROOT']. '/upload/';
        $new_random_name = str_replace('==','', base64_encode(openssl_random_pseudo_bytes(10)));
        $extension = new SplFileInfo($_FILES[$input_name]['name']);
        $uploadfile = $uploaddir . $new_random_name .'.'. $extension->getExtension();

        if(move_uploaded_file($_FILES[$input_name]['tmp_name'], $uploadfile)) {
            return $new_random_name .'.'. $extension->getExtension();
        } else {
            return FALSE;
        }
    }

    public function create($id = 0) {
        if (empty($_POST['submited'])) {
            $this->content->view($this->view, "", array_merge(array('state' => "create"), $this->data));
        } else {
            $data = $this->_request();

            $foto_name = $this->uploadfile('foto');
            if($foto_name !== FALSE) {
                $data['foto'] = $foto_name;
            }
            foreach ($data as $k=>$item) {
                if(empty($item))
                    unset($data[$k]);
            }

            $this->db->insert($this->table, $data);

            //add bonus
            $this->load->model('accaunt_model', 'accaunt');
            if (!empty($data["id_user"]))
                $this->accaunt->payBonusesOnAddVideo($data["id_user"]);

            $this->element_id = $this->db->insert_id();
            if ($this->info_state)
                $this->info->add("add");
            if ($this->redirect == true)
                return;
            redirect($this->all);
        }
    }
    public function ajax_delete_photo($id) {
        $this->load->model('video_model', 'video_model');
        if($this->video_model->delete_photo($id) === TRUE)
            echo 'Изображение было удаленно';
        else 
            echo 'Не удалось удалить фото';

    }
}
