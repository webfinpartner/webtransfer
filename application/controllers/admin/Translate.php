<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class Translate  extends Permissions_controller
{
    public $info_state = true;
    public function __construct(){
                 parent::__construct();
                admin_state();
                
                if ($this->info_state)
                    $this->load->library('info');
                
    }
    
     public function all(){
         
        $data = [];
        if (!empty($_POST['submited'])) {         
            $this->load->model('Translate_model');
            $data['text'] = $this->Translate_model->find_untransalated_strings(TRUE);
            
        }
        $this->content->view('translate', 'Перевод', $data);  
     }


     
  
    

}