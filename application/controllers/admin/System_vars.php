<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Admin_controller')){ require APPPATH.'libraries/Admin_controller.php';}
class  System_vars extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_vars_model');

	$data=array(
                    'description'=>'description',
                    'human_name'=>'human_name',
                    'machine_name'=>'machine_name',
                    'default_value'=>'default_value', 
                    'value'=>'value' ); 			

        $setting=array('ctrl'=>'system_vars',
                                'view'=>'system_var',
                                'table'=>'system_vars',
                                'argument'=>$data,
				'image'=>'logo'
		  );
	 //$this->index_redirect=false;
	 $this->setting($setting);
         
	 $this->data['view_all']=array(
		"one"=>"Системную переменную",
		"many"=>"системными переменными",
		"fields"=>array(                    
                    "Переменная"=>"machine_name",
                    'Имя переменной'=>'human_name',
                    "Значение по умолчанию"=>"default_value",
                    "Значение"=>"value",
                    'Описание'=>'description',
                    )
                  ); 	         
    }
    
    
    
    /**
     * Action: Список всех системных переменных
     */
    public function all() {

        if (!isset($this->data['items']))
            $this->data['items'] = $this->System_vars_model->get_all();
        
        $this->load->model('currency_exchange_statistic_model','currency_exchange_statistic');
        $this->data['rates'] = $this->currency_exchange_statistic->get_last_all_average_data();
        
        //vred($this->data['rates']);
        
        $this->content->view('system_vars_view_all_tpl', $this->title, $this->data);
        
    
    }
    /**
     * Action: редактирование и просмотр системной переменной c ID=$id
     * @param int $id
     */
     
    public function index($id = 0) {
        $id = intval($id);
        $this->element_id = $id;
        if (empty($id))
            redirect($this->all);

        if (!empty($_POST['submited'])) {
            $data = $this->_request();
            $res = $this->System_vars_model->edit($data, $id);
            // если при сохранение произошла какая либо ошибка то покажем ее
            if ( $res === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = $id;
                if ($this->info_state) $this->info->add($this->System_vars_model->get_last_error());
                $this->content->view($this->view, '', $this->data);
                return;
            }            
            if ($this->info_state) $this->info->add("edit");
            redirect($this->all);                
        }
        

        if (!isset($this->data['item']))
            $this->data['item'] = $this->System_vars_model->get($id);

        
        $this->data['state'] = $id;
        
        $this->content->view($this->view, '', $this->data);
        
    }
    /**
     * Action: Создание системной переменной
     */
    public function create($id = 0) {
        if (empty($_POST['submited'])) {            
            $this->data['state'] = 'create';
            $this->content->view($this->view, "",  $this->data);
        } else {
            $data = $this->_request();
            $this->element_id = $this->System_vars_model->create($data);
            // если при сохранение произошла какая либо ошибка то покажем ее
            if ( $this->element_id === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = 'create';
                if ($this->info_state) $this->info->add($this->System_vars_model->get_last_error());
                $this->content->view($this->view, '', $this->data);
                return;
            }
            
            
            if ($this->info_state)$this->info->add("add");
            redirect($this->all);
        }
    }
    
    /**
     * Action: Удаление системной переменной c ID=$id
     * @param int $id
     */
    public function delete($id = 0) {
        $id = intval($id);
        $this->System_vars_model->remove($id);
        if ($this->info_state)
            $this->info->add("delete_yes");
        redirect($this->all);
    }	


	

}