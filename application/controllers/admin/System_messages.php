<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class  System_messages extends Permissions_controller
{
    public $info_state = true;
    public function __construct(){
                 parent::__construct();
                admin_state();
                $this->load->model('System_messages_model');
                
                $this->load->helper( 'form' );
                
                if ($this->info_state)
                    $this->load->library('info');
                
    }
    
 
    /**
     * Action: главная страница для редактирования системных сообщений
     */
     public function all(){
        $this->content->view('system_messages', 'Системные сообщения', array());  
     }


     
    public function index($id = 0) {
        
        
        $id = intval($id);
        $this->element_id = $id;
        
        $this->data['statuses'] = $this->System_messages_model->get_statuses();
        $this->data['types'] = $this->System_messages_model->get_types();
        
        
        
        if (empty($id))
        {
                 
                 $page = 0;
                 $rows = 0;
                 $sort = array();
                 if (!empty($_POST['rows']) && !empty($_POST['page'])){
                     $page = (int)$_POST['page'] - 1;
                     $rows = (int)$_POST['rows'];
                 }
                 
                 if (!empty($_POST['sort']) && !empty($_POST['order'])){
                     $arrSort = explode(',',$_POST['sort']);
                     $arrOrder = explode(',',$_POST['order']);
                     if ( !empty( $arrSort ) )
                        foreach($arrSort as $idx=>$field)
                            $sort[ $field ] = $arrOrder[ $idx ];
                 }
                 
                 $recs = $this->System_messages_model->get_all_messages($recsCount, $page,$rows,$sort);
                 
                 
                 $cnt = count($recs);
                 $resultArray = array(
                        'rows' => array(),
                        'total' => $recsCount
                    );
                  foreach( $recs as &$rec )
                  {

                      $rec['action'] = '<nobr><a title="Редактировать" class="smallButton" href="/opera/system_messages/'.$rec['id'].'" ><img src="images/icons/018.png"></a>';
                      $rec['action'] .= '<a title="Удалить" class="smallButton del" onclick="return yes_no()" href="/opera/system_messages/delete/'.$rec['id'].'" ><img src="images/icons/101.png"></a></nobr>';
                      
                      $resultArray['rows'][] = $rec;
                  }
                    

                  echo json_encode($resultArray);
                  return;
        }

        if (!empty($_POST['submited'])) {
            
            $data = $_POST;
            $res = $this->System_messages_model->edit_message($id, $data);
            // если при сохранение произошла какая либо ошибка, то покажем ее
            if ( $res === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = $id;
                if ($this->info_state) $this->info->add($this->System_messages_model->get_last_error());
                $this->content->view('system_messages_item', '', $this->data);
                return;
            }            
            if ($this->info_state) $this->info->add("edit");
            redirect(  base_url() . 'opera/system_messages/all'  );                
        }
        

        if (!isset($this->data['item']))
            $this->data['item'] = $this->System_messages_model->get($id);

        
        $this->data['state'] = $id;
        //$this->data['messages'] =  $this->System_messages_model->get_all_messages($recsCount,0,0);
        $this->content->view('system_messages_item', '', $this->data);
        
    }
    /**
     * Action: Создание системного сообщения
     */
    public function create($id = 0) {
            $this->data['statuses'] = $this->System_messages_model->get_statuses();
            $this->data['types'] = $this->System_messages_model->get_types();
        
        if (empty($_POST['submited'])) {            
            $this->data['state'] = 'create';
            $this->content->view('system_messages_item', "",  $this->data);
        } else {
            $data = $_POST;
            $this->element_id = $this->System_messages_model->create_message($data);
            // если при сохранение произошла какая либо ошибка то покажем ее
            if ( $this->element_id === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = 'create';
                if ($this->info_state) $this->info->add($this->System_messages_model->get_last_error());
                $this->content->view('system_messages_item', '', $this->data);
                return;
            }
            
            
            if ($this->info_state)$this->info->add("add");
            redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
        }
    }
    
    /**
     * Action: Удаление системного сообшения c ID=$id
     * @param int $id
     */
    public function delete($id = 0) {
        $id = intval($id);
        if ( $this->System_messages_model->remove_message($id, true) !== FALSE){
            if ($this->info_state)
                $this->info->add("delete_yes");
            
        } else {
            if ($this->info_state)
                $this->info->add( $this->System_messages_model->get_last_error());            
        }
        redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
    }	
    
    

}