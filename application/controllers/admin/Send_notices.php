<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Table_controller')){ require APPPATH.'libraries/Table_controller.php';}
class Send_notices extends Table_controller {

    public function __construct()
    {
        parent::__construct();
        admin_state();
        $this->load->model('Send_notices_model', 'send_notices');
    }
    
    
    public function all(){
        $data['sends']=$this->send_notices->get_all();
        // Подключение layout (шаблона) отображения
        $this->content->view('send_notices',"",$data);        
    }

    

    /*
     *  Выводит страницу для нового сообщения
     */
    public function create($id=0)
    {
        $data['item']->id = '';
        $data['mode'] = 'create';
        $this->content->view('send_notices_item','',$data);
    }    
    
    
    public function ajax_search()
    {
        $type = $this->input->post('type');
        $q = $this->input->post('q');
        $result = array();
        
        if ( empty($q) || empty($type)){
            echo json_encode($result);
            return;
        }
        
        
        switch($type){
            case 'user_id':
                $this->db->where('id_user', $q);
                break;
            case 'fio':
                 $fio = explode(' ',$q);
                 if ( count($fio) > 0 )
                    $this->db->where('sername', $this->code->code( $fio[0] ));
                 if ( count($fio) > 1)
                    $this->db->where('name', $this->code->code( $fio[1] ));
                 if ( count($fio) > 2)
                    $this->db->where('patronymic', $this->code->code( $fio[2] ));
                break;
            case 'email':
                 $this->db->where('email', $this->code->code($q));
                break;
            case 'phone':
                 $this->db->where('phone', $this->code->code($q));
                break;
            default:
                echo json_encode($result);
                return;
        }
        $query = $this->db->get('users')->result();
        //echo $this->db->last_query();
        if ( empty($query) ){
            echo json_encode($result);
            return;
        }
        
        $this->load->model('Accaunt_model', 'account');
        $this->load->model('Security_model');
        foreach ( $query as  $row){
            $decoded_user = $this->account->getUserFields($row->id_user, array('id_user','sername','name','patronymic','phone','email') );
            if (!empty($decoded_user) ){
                $user = (array)$decoded_user;
                $user['security'] = @$this->Security_model->getProtection(0,$row->id_user)->value;
                $result[] = $user;
            }
        }
        echo json_encode($result);
    }

    /*
     *  Выводит страницу выбранного уведомления, либо сохраняет  уведомление
     */
    public function index($id=0)
    {

        if(!empty($_POST['submited']))
        {
            $message = $this->input->post('message');
            $recipients = $this->input->post('recipients');
            $recipients = explode(',', $recipients);
            
            if ( empty($message) || empty($recipients) )
                redirect( base_url() . 'opera/send_notices/create'  );    
            
            $result_id = $this->send_notices->save($message, $recipients);
            if ($result_id ===FALSE)
                redirect( base_url() . 'opera/send_notices/create'  );    
            
            redirect( base_url() . 'opera/send_notices/'.$result_id  );
        }
        $data['mode'] = 'view';
        $data['item'] = $this->send_notices->get($id);
        
        
       $this->content->view('send_notices_item','', $data);
    }

}
