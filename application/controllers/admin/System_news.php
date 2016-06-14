<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Admin_controller')){ require APPPATH.'libraries/Admin_controller.php';}
class System_news extends Admin_controller
{
    /**
    * Конструктор класса с проверкой авторизации
    */
	 public function __construct()
    {
        parent::__construct();
        $data=array('title', 'text', 'data', 'url', 'foto' );
        $this->id="id_new";
	$this->safe_url="title";
      $setting=array('ctrl'=>'system_news',
      					'view'=>'new',
      					'table'=>'system_news',
      					'argument'=>$data,
      					'image'=>'system_news');
		$this->setting($setting);
						$this->data['view_all']=array(
					"one"=>"Системную Новость",
					"system_news"=>1,
					"many"=>"Системными Новостями Сайта",
					"fields"=>array(
						"Картинка"=>"<img src=\"/upload/imager.php?src={$this->image_folder}/{foto}&w=60\" />",
						"Заголовок"=>"title","Ссылка"=>"url",
					      "Дата"=>"data"));
    }
}
