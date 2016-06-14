<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Permissions_controller')) {
    require APPPATH . 'libraries/Permissions_controller.php';
}

class admin_profile extends Permissions_controller {
    public $info_state = true;
    
    public function index() {
	
        admin_state();
         $this->load->model("admin_model", "admin");
         $this->load->library('info');
        if (!empty($_POST['submited'])) {

            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'Новый пароль', 'required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('password2', 'Подтверждение  пароля', 'required|matches[password]');
            if ($this->form_validation->run() == true) {
                $this->info->add("edit");
                $this->db->where('id_user', $this->admin_info->id_user)->update('admin', array('password' => $this->input->post('password')));
            }
        }
        
        if (!empty($_POST['method'])) {
            switch ($_POST['method']){
                case 'get_auth_window':
                        $sec_auth = $_POST['sec_auth'];
                        $cur_sec_auth = $this->admin_info->sec_auth;
                        // если текущая безопасность по смс то сразу отправим смс
                        if ( $cur_sec_auth == 'sms'){
                            if (!empty($this->admin_info->id_user)) {
                                if ($this->admin->sendSms($this->admin_info->id_user) !== true){
                                    echo 'Не удалось отправить смс-код. Попробуйте позже';
                                    return;
                                }
                            } else {
                                    echo 'Не удалось отправить смс-код. Попробуйте позже';
                                    return;
                            }

                        }
                        echo $this->load->view('admin/blocks/security_check_code', ['cur_sec_auth'=>$cur_sec_auth, 'sec_auth'=>$sec_auth], true);
                        return;                    
                    break;
                case 'check_code':
                     require_once APPPATH.'libraries/Permissions.php';
                     $permissions = Permissions::getInstance();
                     $check_result = $permissions::checkSecurity($this->admin_info->id_user);
                     
                     $res = ['status' => $check_result];
                     if ( $check_result ){
                         $this->admin->setProtectType($this->admin_info->id_user,  $_POST['set_to_auth']);
                         if ( isset($_POST['link_user_id']) )
                            $this->admin->linkToUsers($this->admin_info->id_user,  $_POST['link_user_id']);    
                         $res['status_text'] = 'Тип авторизации успешно изменен';
                        if ($this->info_state) $this->info->add($res['status_text']);                         
                         
                     } else 
                         $res['status_text'] = 'Неверный код';
                     
                     
                         
                     
                     
                     echo json_encode( $res );
                     return;
                    break;
                
                
                
                
            }
            

           
        }        
 
        
        $this->content->view('admin_profile');        
    }
    

}

?>
