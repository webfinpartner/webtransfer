<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Admins extends Admin_controller {

    public function __construct() {
        parent::__construct();


         $this->load->model('System_events_model');

        $data = array('name', 'family', 'email', 'doljnost', 'otdel', 'telephone', 'login', 'password', 'permission');
        $setting = array('ctrl' => 'admins', 'table' => 'admin', 'argument' => $data );
        $this->id = 'id_user';
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Модератора",
            "many" => "Модераторами",
            "fields" => array(
                "Имя" => "name",
                "Фамилия" => "family", "Должность" => "doljnost",
                'Отдел' => 'otdel', "Телефон" => "telephone", 'email' => 'email', 'login' => "login"
        ));
    }

    function delete($id = 0) {
        if ($id == 46773) {
            redirect($this->all);
            exit;
        } else
            parent::delete($id);
    }
    
    public function index($id=0)
    {
         $this->data['events']= $this->System_events_model->get_all_events();
        parent::index($id);
    }
    
    /**
     * Получить подписки на системные события
     * @param type $user_id
     */
    function event_subscribes($user_id = 0){
        
        $res = array();   
        
        $data = $this->System_events_model->get_subscribes($user_id);
           
        foreach( $data as &$row){
            $row->enabled_txt = ($row->enabled==1?'ДА':'НЕТ');
            $res[] = $row;
        }
           
           
        echo json_encode($res);
        
        
        
    }

    
    /**
     * Получить настройки системных событий
     * @param type $p
     */
    function event_params($event_id){
           $res = array();  
           $user_id = $this->input->post('user_id');
           
           
           
           $data = $this->System_events_model->get_event_params($event_id, $user_id);
           
           
           $res['rows'] = array();
           
           foreach( $data as $group=>$rows ){
               $res['rows'][] = array(
                   "name"=>'Тип',
                   "value"=>$rows['type'],
                   'json_name' => 'type',
                   "group" => $group,
                   "editor" => array(
                       'type' => 'combobox',
                       'options' => array(
                           'valueField' => 'value',
                           'textField' => 'text',
                           'data' => array(
                               array('text'=>'-', 'value'=>''),
                               array('text'=>'sms', 'value'=>'sms'),
                               array('text'=>'email', 'value'=>'email'),
                               array('text'=>'viber', 'value'=>'viber'),
                               array('text'=>'whatsapp', 'value'=>'whatsapp')
                               
                           )
                       )
                   )
                   
               );
               $res['rows'][] = array(
                   "name"=>'Адрес',
                   "value"=>$rows['addr'],
                   'json_name' => 'addr',
                   "group" => $group,
                   "editor" => 'text'
                   
               );
               $res['rows'][] = array(
                   "name"=>'MAX сообщений в час',
                   'json_name' => 'max_messages_in_hour',
                   "value"=>$rows['max_messages_in_hour'],
                   "group" => $group,
                   "editor" => 'text'
                   
               );
               
           }
           
           $res['total'] = count($res['rows']);
           
           echo json_encode($res);
        
        
        
    } 
    
    
    /**
     * Присоединить событие к пользователю
     * @param type $id
     */
    function link_event($subscribe_id=0){
        
        $admin_id = $this->input->post('admin_id');
        $event_id = $this->input->post('event_id');
        $params = $this->input->post('params');
        $enabled = $this->input->post('enabled');
        
        if ( empty($admin_id) || empty($event_id) || empty($params) || !isset($enabled) ){
            echo 'Ошибка в параметрах';
            return;
        }
        
        
        $this->System_events_model->link_event($subscribe_id, $admin_id, $event_id, $enabled, $params);
        echo 'OK';
        
    }
    
    /**
     * Отсоединить событие от пользователя
     * @param type $id
     */
    function unlink_event($subscribe_id){
        $this->System_events_model->unlink_event($subscribe_id);
        echo 'OK';
    }
    
}
