<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class Whitelabel extends Permissions_controller
{

    public function __construct()
    {
        parent::__construct();


    }
    
    
    public function all(){
        
        
        
         $data = array();
         $this->content->view('whitelabel', 'Сайты-партнеры', $data);
    }
    
    
    public function partners_list(){
        $this->load->model('Whitelabel_model','whitelabel');
        $list = $this->whitelabel->get_partners_list();        
        echo json_encode($list);
    }
    
    
    
    
}