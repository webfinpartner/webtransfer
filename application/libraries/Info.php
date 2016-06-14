<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Info
{

	function __construct()
	{
		$this->ci =& get_instance();
	}

	function add($state)
	{
		$this->ci->session->set_userdata(array("error"=>$state));
	}

	function get_info()
	{

		$action=$this->ci->session->userdata('error');
		$this->ci->session->set_userdata(array("error"=>"empty"));
		switch($action){
		case "delete_yes":  return "Успешно удалено";  break;
		case "delete_no":  return "Не может быть удалено, так как содержит детей";   break;
		case "delete_no_user": return "Не может быть удалено, так как у  пользователя  есть  активный кредит или вклад";   break;
		case "edit"  : return "Изменения успешно сохранены"; break;
		case "add"   : return "Данные успешно добавленны";  break;
		case "add_news": return "Новость успешно добавлена"; break;
		case "add_faq": return "Пост успешно добавлен"; break;
		case "add_banner": return "Баннер успешно добавлен"; break;
                default:
                    return $action;
		}
	}
}
