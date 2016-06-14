<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Admin_controller')){ require APPPATH.'libraries/Admin_controller.php';}
class Sender extends Admin_controller
{

    public function __construct()
    {
		parent::__construct();

        $data=array( 'title', 'mail', 'name','text', 'active', 'state', 'lang', 'master' );

        $setting = array('ctrl' => 'sender', 'view' => 'sender', 'table' => 'sender', 'argument' => $data);
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Уведомление",
            "many" => "Уведомениями",
            "fields" => array(
                "Заголовок" => "title", "Получатель" => "{function:state_check(state)}", "Название" => "name",
                "Текст" => "{function:cutString(text:50)}", "action" => "{function:admin_active(active)}"
        ));
    }
}

function state_check($state)
{
	return ($state==1)?'Админ':'Пользователь';
}